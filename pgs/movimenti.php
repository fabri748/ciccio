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

<h1>
	<a href="index.php">
		Movimenti
	</a>
</h1>
<h2>
Filtra per data
</h2>
<form method="post" action="?p=movimenti"> <!--FORM PER IL FILTRO DATA CONTROLLARE IL CAMPO ACTION CHE PUNTI ALLA PAGINA CORRETTA-->
        <label for="da">
            DAL 
        </label>
        <input name="dal" type="date"  value="<?= $dal ?>"   />
        <label for="a">
            AL
        </label>
        <input name="al"  type="date" value="<?= $al ?>"   />

        <button type="submit" tabindex="-1">
            Filtra
        </button>

    </form> <!--FINE FORM FILTRO DATA -->
<h4 class="msg"><?=$msg?></h4>
<div class="tablecontainer">
	<table class="full table table-striped table-bordered responsive" id="tabella" >
		<colgroup>
			<col style="width:150px" />
		</colgroup>
		<thead>
			<tr>
				<th>Data</th>
				<th>Descrizione</th>
				<th>Categoria</th>
				<th>Importo</th>
				<th style="width:60px;text-align:center">Modifica</th>
				<th style="width:60px;text-align:center">Cancella</th>
			</tr>
		</thead>
		<tbody>
		<? foreach ($movimenti as $r) : ?>
		<? if ($u==$r->id) : ?>
			<tr>
				<td>
					<input type="date"  name="datamovimento" value="<?=date('Y-m-d',strtotime($r->datamovimento))?>"  onchange="chg(this)"   autofocus />
				</td>
				<td>
					<input name="movimento" value="<?=$r->movimento?>" onchange="chg(this)" />
				</td>
				<td>
					<select name="categorie_id" placeholder="categoria" onchange="chg(this)" >	
						<option></option>
						<? foreach ($categorie as $cat) : ?>
							<option value="<?=$cat->id?>" <?=($r->categorie_id==$cat->id) ? 'selected' : '' ?> >  <?=$cat->categoria ?> </option>
						<? endforeach ?>
					</select>
				</td>
				<td>
					<input name="importo" type="number" step="any" value="<?=$r->importo?>" onchange="chg(this)"  style="text-align:right" />
				</td>
				<td>
					<form id="frm" method="POST" action="?p=movimenti">
						<input type="hidden" name="id" value="<?=$r->id?>" />
						<input type="hidden" name="datamovimento" value="<?=$r->datamovimento?>" />
						<input type="hidden" name="movimento" value="<?=$r->movimento?>" />
						<input type="hidden" name="categorie_id" value="<?=$r->categorie_id?>" />
						<input type="hidden" name="importo" value="<?=$r->importo?>" />
						<button type="submit" class="">
							Salva
						</button>
					</form>
				</td>
				<td>
					&nbsp;
				</td>							
			</tr>
		<? else : ?>
			<tr>
				<td>
					<?=date('d/m/Y',strtotime($r->datamovimento))?>
				</td>
				<td>
					<p>
						<?=$r->movimento?>
					</p>
				</td>
				<td>
						<?=($r->categorie_id) ? $r->categorie->categoria : ''?>
				</td>
				<td style="text-align:right" >
					<?=$r->importo?>
				</td>
				<td style="text-align:center" >
					<a class="btn btn-sm btn-" href="?p=movimenti&upd=<?=$r['id']?>">
						Mod.
					</a>
				</td>
				<td style="text-align:center" >
					<a class="btn btn-sm btn-danger" href="?p=movimenti&del=<?=$r['id']?>" tabindex="-1">
						x
					</a>
				</td>							
			</tr>		
		<? endif; ?>
		<? endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
                        <th colspan="5" style="text-align:right"></th>
                        
                    </tr>
		</tfoot>
	</table>
	</div>
	<h2 style="text-align:right">Il bilancio al: <b><?php echo date("d/m/Y");  ?></b> è di:  <b><?php echo $bilancio;  ?> €</b></h2>
		</br>
	
		<? if (!$u) : ?>
			<div class="tablecontainer" style="max-width: 50%">
				<table class="table responsive">
				<thead>
			<tr>
				<th>Data</th>
				<th>Descrizione</th>
				<th>Categoria</th>
				<th>Importo</th>
				
			</tr>
		</thead>
				<tr >
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
				</table>
			</div>
		<? endif; ?>
		

<script>
	var chg=function(e){
		document.forms.frm.elements[e.name].value=(e.value) ? e.value : null
		//if (e.options && e.options[e.options.selectedIndex]) document.forms.frm.elements[e.name].value=e.options[e.options.selectedIndex].value
	}	
</script>
</script>
<script src="https://code.jquery.com/jquery-3.1.1.js" ></script>

<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" ></script>



<script>
    $(document).ready(function () {
//DATATABLE
//metto alla variabile otable la mia tabella che ho creato
        $('#tabella').dataTable({ // ASSEGNARE L'ID DELLA TABELLA 
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function (i) {
                    return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                };
                // Total over all pages
                total = api
                        .column(3) //COLONNA SU CUI ESEGUIRE LA SOMMA (SI PARTE A CONTARE DA 0)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                // Total over this page
                pageTotal = api
                        .column(3, {page: 'current'}) //COLONNA SU CUI ESEGUIRE LA SOMMA (SI PARTE A CONTARE DA 0)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                // Update footer
                $(api.column(3).footer()).html( //COLONNA SU CUI ESEGUIRE LA SOMMA (SI PARTE A CONTARE DA 0)
                        '€' + pageTotal + 'Totale della selezione '
                        );
            }
        });
    });
</script>