<?php

//Добавить картинку в цепочку
function action_chain_add( &$args )
{
	if (!user_access_check($args))
		return false;

	$evt_id  = intval( request_get( $args, 'evt_id'  ));
	$scn_id  = intval( request_get( $args, 'scn_id'  ));
	$row_idx = intval( request_get( $args, 'row_idx' ));
	$chn_idx = intval( request_get( $args, 'chn_idx' ));
	$image_type = intval( request_get( $args, 'image_type' ));
	$exp_id = intval( request_get( $args, 'exp_id' ));
	
	if ($image_type != 0 and $image_type != 1)
		return false;

	if ($exp_id == 0) //Не выбран эксперт
		return false;

	$err_type_id = $image_type == 0 ? 6 : 5;
	
	/*$ret = db_query_rpd("INSERT INTO cik_chains ( evt_id, scn_id, row_idx, chn_idx, image_type, exp_id ) 
		VALUES ( {$evt_id}, {$scn_id}, {$row_idx}, {$chn_idx}, {$image_type}, {$exp_id} );
		INSERT INTO cik_final_form (evt_id, error_code, scn_id, scn_row_idx, exp_id, chn_idx) 
		VALUES ({$evt_id}, (select code from cik_error_codes 
		WHERE evt_id = {$evt_id} AND err_type_id = {$err_type_id}), {$scn_id}, {$row_idx}, {$exp_id}, {$chn_idx});", true);
		*/
	$ret = db_query_rpd("INSERT INTO cik_final_form (evt_id, error_code, scn_id, scn_row_idx, exp_id, chn_idx) VALUES (
		 {$evt_id}, 
		 (SELECT code FROM cik_error_codes WHERE evt_id = {$evt_id} AND err_type_id = {$err_type_id}),
		 {$scn_id}, 
		 {$row_idx}, 
		 {$exp_id}, 
		 {$chn_idx}
		 );", true);

	return $ret > 0;
}
