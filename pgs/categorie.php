<?php 
	if (!empty($_REQUEST['del'])) : 
		$categoria=R::load('categorie', intval($_REQUEST['del']));
		try{
			R::trash($categoria);
		} catch (RedBeanPHP\RedException\SQL $e) {
			$msg=$e->getMessage();
		}
	endif;	
	if (!empty($_POST['categoria'])) : 
		if (empty($_POST['id'])){
			$categoria=R::dispense('categorie');
		}else{
			$categoria=R::load('categorie',intval($_POST['id']));
		}
		$categoria->categoria=$_POST['categoria'];

		try {
			$id=R::store($categoria);
		} catch (RedBeanPHP\RedException\SQL $e) {
			?>
			<h4 class="msg label error">
				<?=$e->getMessage()?>
			</h4>
			<?
		}	
	endif;
	
	$categorie=R::findAll('categorie');
?>
<h1>
	<a href="index.php">
		Categorie
	</a>
	
</h1>
<section class="">
	<?php foreach ($categorie as $cat) : ?>
		<article class="card cc">
			<form method="post" action="?p=categorie">
				<input name="categoria"  value="<?=$cat->categoria?>"  />
				<input type="hidden" name="id" value="<?=$cat->id?>" />
				<button type="submit" tabindex="-1">
					Salva
				</button>
				<a href="?p=categorie&del=<?=$cat['id']?>" class="button dangerous" tabindex="-1">
					Elimina
				</a>					
			</form>
		</article>
	<?php endforeach; ?>
		<article class="card cc">
			<form method="post" action="?p=categorie">
				<input name="categoria" placeholder="Nuova categoria" autofocus />
				<button type="submit">
					Inserisci
				</button>
			</form>
		</article>
</section>