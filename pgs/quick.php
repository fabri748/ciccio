<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$msg='';
	$dal = (empty($_POST['dal'])) ? '1970-01-01' : $_POST['dal']; //queste tre righe servono per 
	$al = (empty($_POST['al'])) ? date('Y-m-d') : $_POST['al'];   //il filtraggio delle date
	//$al = date('Y-m-d');
	          
	
	$u = (!empty($_REQUEST['upd'])) ? intval($_REQUEST['upd']) : false;
	if ($u) $movimento=R::load('movimenti', $u);
	if (!empty($_REQUEST['importo'])) : 
		$movimento=(empty($_REQUEST['id'])) ?  R::dispense('movimenti') : R::load('movimenti', intval($_REQUEST['id']));
		$movimento->datamovimento = $_POST['datamovimento']; 
		$movimento->movimento = $_POST['movimento'];
		$movimento->categorie_id = (!empty($_POST['categorie_id'])) ? $_POST['categorie_id'] : null;
		$movimento->importo = $_POST['importo'];
		try {
			R::store($movimento);
		} catch (RedBeanPHP\RedException\SQL $e) {
			$msg=$e->getMessage();
		}
	endif;	
	
	if (!empty($_REQUEST['del'])) : 
		$movimento=R::load('movimenti', intval($_REQUEST['del']));
		try{
			R::trash($movimento);
		} catch (RedBeanPHP\RedException\SQL $e) {
			$msg=$e->getMessage();
		}
	endif;
	
	$movimenti = R::find('movimenti', 'datamovimento BETWEEN "' . $dal . '" AND "' . $al . '" ORDER by id ASC LIMIT 999'); // sostituisco il data qua sotto con questa versione
	//$movimenti=R::findAll('movimenti', 'ORDER by id ASC LIMIT 999');
	$categorie=R::findAll('categorie');
	
	$bilancio=R::getCell('select SUM(importo) from movimenti');
	$today=date('Y-m-d');
	
?>


		<? if (!$u) : ?>
			<div>
				<tr style="background:lightyellow">
					<td>
						<input type="date" name="datamovimento" value="<?=date('Y-m-d') ?>" onchange="chg(this)" max="<?=$today?>"  autofocus />
					</td>
					<td>
						<input name="movimento" value="" onchange="chg(this)" />
					</td>
					<td>
						<select name="categorie_id" placeholder="categoria" onchange="chg(this)" >	
							<option></option>
							<? foreach ($categorie as $cat) : ?>
								<option value="<?=$cat->id?>">  <?=$cat->categoria ?> </option>
							<? endforeach ?>
						</select>
					</td>
					<td>
						<input name="importo" type="numer" step="any" value="" onchange="chg(this)" />
					</td>
					<td>
						<form id="frm" method="POST" action="?p=movimenti">
							<input type="hidden" name="datamovimento" value="<?=date('Y-m-d')?>" />
							<input type="hidden" name="movimento" value="" />
							<input type="hidden" name="categorie_id" value="" />
							<input type="hidden" name="importo" value="" />
							<button type="submit" class="button">
								Salva
							</button>
						</form>
					</td>
					<td>
						&nbsp;
					</td>							
				</tr>		
			</div>
		<? endif; ?>
		</div>

<script>
	var chg=function(e){
		document.forms.frm.elements[e.name].value=(e.value) ? e.value : null
		//if (e.options && e.options[e.options.selectedIndex]) document.forms.frm.elements[e.name].value=e.options[e.options.selectedIndex].value
	}	
</script>
</script>
<script src="https://code.jquery.com/jquery-3.1.1.js" ></script>

<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" ></script>



