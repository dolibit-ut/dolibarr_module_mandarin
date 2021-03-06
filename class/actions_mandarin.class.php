<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2015 ATM Consulting <support@atm-consulting.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    class/actions_mandarin.class.php
 * \ingroup mandarin
 * \brief   This file is an example hook overload class file
 *          Put some comments here
 */

/**
 * Class Actionsmandarin
 */
class Actionsmandarin
{
	/**
	 * @var array Hook results. Propagated to $hookmanager->resArray for later reuse
	 */
	public $results = array();

	/**
	 * @var string String displayed by executeHook() immediately after return
	 */
	public $resprints;

	/**
	 * @var array Errors
	 */
	public $errors = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Overloading the doActions function : replacing the parent's function with the one below
	 *
	 * @param   array()         $parameters     Hook metadatas (context, etc...)
	 * @param   CommonObject    &$object        The object to process (an invoice if you are in invoice module, a propale in propale's module, etc...)
	 * @param   string          &$action        Current action (if set). Generally create or edit or null
	 * @param   HookManager     $hookmanager    Hook manager propagated to allow calling another hook
	 * @return  int                             < 0 on error, 0 on success, 1 to replace standard code
	 */
	function addMoreActionsButtons($parameters, &$object, &$action, $hookmanager)
	{
		
		 $error = 0;
		if (in_array('pricesuppliercard', explode(':', $parameters['context'])))
		{
		 	global $conf,$user,$langs;
		 	if ( !empty($conf->global->MANDARIN_TRACE_COST_PRICE) && !empty($user->rights->mandarin->graph->product_cost_price)) {

					
		        	define('INC_FROM_DOLIBARR',true);
		        	dol_include_once('/mandarin/config.php');			
	        		dol_include_once('/mandarin/class/costpricelog.class.php');
				
				$PDOdb=new TPDOdb;

	        		        
				$TData = TProductCostPriceLog::getDataForProduct($PDOdb, $object->id);
				if(!empty($TData)) {
				
					$l=new TListviewTBS('graphrate');
					echo $l->renderArray($PDOdb, $TData,array(
						'type'=>'chart'
						,'curveType'=>'none'
						,'liste'=>array(
							'titre'=>$langs->trans('GraphTraceCostPrice')
						)
						,'title'=>array(
							'PA'=>$langs->transnoentities('PricePA')
							,'PMP'=>$langs->transnoentities('PricePMP')
							,'OF'=>$langs->transnoentities('PriceOF')
						)
					));

					
					?>
					<script type="text/javascript">
						$(document).ready(function() {
							$('#div_query_chartgraphrate').insertAfter('div.fiche:first');
						});
						
					</script>
					<?php
				}
				
				
					
			}
			
		 
		}

		if (! $error)
		{
			return 0; // or return 1 to replace standard code
		}
		else
		{
			$this->errors[] = 'Error message';
			return -1;
		}
	}
}
