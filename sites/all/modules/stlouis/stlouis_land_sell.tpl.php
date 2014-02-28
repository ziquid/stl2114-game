<?php

  global $game, $phone_id;

  $fetch_user = '_' . arg(0) . '_fetch_user';
  $fetch_header = '_' . arg(0) . '_header';

  $game_user = $fetch_user();
  include(drupal_get_path('module', $game) . '/game_defs.inc');
  $arg2 = check_plain(arg(2));

  if ($quantity === 'use-quantity') {
    $quantity = check_plain($_GET['quantity']);
  }

  $data = array();
  $sql = 'SELECT land.*, land_ownership.quantity,
    competencies.name as competency
    FROM land
    
    LEFT OUTER JOIN land_ownership ON land_ownership.fkey_land_id = land.id
    AND land_ownership.fkey_users_id = %d

    LEFT OUTER JOIN competencies on land.fkey_required_competencies_id =
      competencies.id
    
    WHERE land.id = %d;';
  $result = db_query($sql, $game_user->id, $land_id);
  $game_land = db_fetch_object($result); // limited to 1 in DB
  $orig_quantity = $quantity;
  $land_price = ceil($game_land->price * $quantity * 0.6);

/* allow for 80% sale price
  $land_price = ceil($game_land->price + 
    ($game_land->price_increase * ($game_land->quantity - $quantity))
     * $quantity * 0.8);
*/

  $options = array();
  $options['land-sell-succeeded'] = 'sell-success';
  $ai_output = 'land-succeeded';
  
// check to see if land prerequisites are met

// hit a quantity limit?
  if ($quantity > $game_land->quantity) {

    $options['land-sell-succeeded'] = 'failed not-enough-land';
    $ai_output = 'land-failed not-enough-land';
    _karma($game_user, 
      "trying to sell $quantity of $game_land->name but only has $game_land->quantity",
      $quantity * -10);

  }

// can't sell?
  if ($game_land->can_sell != 1) {

    $options['land-sell-succeeded'] = 'failed cant-sell';
    $ai_output = 'land-failed cant-sell';
    _karma($game_user, "trying to sell unsalable $game_land->name", -100);

  }

// job?
  if ($game_land->type == 'job') { // job?  delete other job(s)

    $options['land-sell-succeeded'] = 'failed cant-sell-job';
    $ai_output = 'land-failed cant-sell-job';
    _karma($game_user, "trying to sell job $game_land->name", -100);

  } // job?
   
  if ($options['land-sell-succeeded'] == 'sell-success') {
    
//    $game_user->money -= $game_land->price;
//    $game_user->income += $game_land->payout;

    if ($game_land->type == 'investment') { // investment?  add competency

      competency_lose($game_user, 'investing', $quantity);

    }

    $sql = 'update land_ownership set quantity = quantity - %d where
      fkey_land_id = %d and fkey_users_id = %d;';
      $result = db_query($sql, $quantity, $land_id, $game_user->id);

    $sql = 'update users set money = money + %d, income = income - %d
      where id = %d;';
    $result = db_query($sql, $land_price, $game_land->payout * $quantity,
      $game_user->id);

    if (substr($game_user->income_next_gain, 0, 4) == '0000') {
// start the income clock if needed

       $sql = 'update users set income_next_gain = "%s" where id = %d;';
      $result = db_query($sql, date('Y-m-d H:i:s', time() + 3600),
         $game_user->id);

    }

    _recalc_income($game_user);
    $game_user = $fetch_user(); // reprocess user object
    
  } else { // failed - add option to try an election
    
    $quantity = 0;
    
  } // sell land succeeded?

  $fetch_header($game_user);
  _show_aides_menu($game_user);  
  
  $game_land->quantity = $game_land->quantity - (int) $quantity;

  _show_land($game_user, $game_land, $options);
  
  echo <<< EOF
<div class="title">
Available $land_plural
</div>
EOF;

  if (substr($phone_id, 0, 3) == 'ai-')
    echo "<!--\n<ai \"$ai_output\"/>\n-->";

  $data = array();
  $sql = 'SELECT land.*, land_ownership.quantity
    FROM land
    
    LEFT OUTER JOIN land_ownership ON land_ownership.fkey_land_id = land.id
    AND land_ownership.fkey_users_id = %d

    WHERE ((
      fkey_neighborhoods_id = 0
      OR fkey_neighborhoods_id = %d
    ) 
    
    AND
    
    (
      fkey_values_id = 0
      OR fkey_values_id = %d
    ))
  
    AND required_level <= %d
    AND active =1
    ORDER BY required_level ASC;';
  $result = db_query($sql, $game_user->id, $game_user->fkey_neighborhoods_id,
    $game_user->fkey_values_id, $game_user->level);

  while ($item = db_fetch_object($result)) $data[] = $item;
  
  foreach ($data as $item) {

    _show_land($game_user, $item);

  }
  
// show next one
  $sql = 'SELECT land.*, land_ownership.quantity
    FROM land
    
    LEFT OUTER JOIN land_ownership ON land_ownership.fkey_land_id = land.id
    AND land_ownership.fkey_users_id = %d

    WHERE ((
      fkey_neighborhoods_id = 0
      OR fkey_neighborhoods_id = %d
    ) 
    
    AND
    
    (
      fkey_values_id = 0
      OR fkey_values_id = %d
    ))
  
    AND required_level > %d
    AND active =1
    ORDER BY required_level ASC LIMIT 1;';
  $result = db_query($sql, $game_user->id, $game_user->fkey_neighborhoods_id,
    $game_user->fkey_values_id, $game_user->level);

  $item = db_fetch_object($result);
firep($item);
    
  if (!empty($item)) _show_land($game_user, $item, array('soon' => TRUE));
  
  db_set_active('default');
