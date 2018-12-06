<?php

require_once 'config/db.php';

if(isset($_POST['save']))
{
	extract($_POST);
	if(!empty($vch_no) && ! empty($entry_name) && ! empty($entry_date) && ! empty($entry_qty) && ! empty($entry_rate) && ! empty($entry_amt) )
	{
		$query = $pdo->prepare("INSERT INTO entries(entry_vch_no, entry_name, entry_date, entry_qty, entry_rate, entry_amt, entry_remarks) VALUES (:v, :n, :d, :q, :r, :a, :rem)");
		$params = array(
			":v" => $vch_no,
			":n" => $entry_name,
			":d" => $entry_date,
			":q" => $entry_qty,
			":r" => $entry_rate,
			":a" => $entry_amt,
			":rem" => $entry_remarks
		);

		if( $query->execute($params) )
		{
			$errorMsg = "Successfully added.";
		}
		else
		{
			$errorMsg = "Error occurred when trying to add.";
		}
	}
	else
	{
		$errorMsg = "Mandatory Input fields cannot be empty.";
	}
}

if(isset($_GET['edit']) && ! empty($_GET['id']))
{
	extract($_GET);
	if($edit == 'yes')
	{
		$q = $pdo->prepare("SELECT * FROM entries WHERE entry_id = :id");
		$q->execute(array(':id' => $id));
		$result = $q->fetch();
		extract($result);
	}

	if (isset($_POST['update'])) {
		extract($_POST);
		$q = $pdo->prepare('UPDATE entries SET entry_vch_no = :evn, entry_name = :en, entry_date = :ed,entry_qty = :eq, entry_rate= :er, entry_amt = :ea, entry_remarks = :erm WHERE entry_id = :id');
		$params = array(
			':evn' => $entry_vch_no,
			':en' => $entry_name,
			':ed' => $entry_date,
			':eq' => $entry_qty,
			':er' => $entry_rate,
			':ea' => $entry_amt,
			':erm' => $entry_remarks,
			':id' => $entry_id
		);
		if ( $q->execute($params) )
		{
			$errorMsg = "Successfully updated.";
		}
		else
		{
			$errorMsg = "Something went wrong with database connection.";
		}
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Add entries</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="main.css">
</head>
<body>
	<header class="app_header">
		<h2 class="text-center">Voucher Entry</h2>
		<div class="text-danger text-center">
			<?php if(isset($errorMsg)) { echo "<p>". $errorMsg . "</p>"; } ?>
		</div>
	</header>
	<div class="app_main">
		<div class="container">
			<div class="offset-md-3">
				<?php if (isset($_GET['edit'])) : ?>
				<form action="#" method="post">
					<div class="form-group">
						<div class="row">
							<input type="hidden" name="entry_id" value="<?php echo $_GET['id']; ?>">
							<div class="col-md-4">
								<input type="text" name="vch_no" id="vch_no" placeholder="Voucher No." class="form-control" value="<?php echo $entry_vch_no; ?>">
							</div>
							<div class="col-md-4">
								<input type="date" name="entry_date" id="entry_date" class="form-control" value="<?php echo date('Y-m-d', strtotime(str_replace('-', '/', $entry_date))); ?>">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<input type="text" name="entry_name" id="entry_name" class="form-control" placeholder="Name" value="<?php echo $entry_name; ?>">
							</div>
							<div class="col-md-2">
								<input type="number" name="entry_qty" id="entry_qty" oninput="multipy();" class="form-control" placeholder="Quantity" value="<?php echo $entry_qty; ?>">
							</div>
							<div class="col-md-2">								
								<input type="number" name="entry_rate" oninput="multipy();" id="entry_rate" class="form-control" placeholder="Rate" value="<?php echo $entry_rate; ?>">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<input type="number" name="entry_amt" id="entry_amt" class="form-control" placeholder="Amount" value="<?php echo $entry_amt; ?>">
							</div>
							<div class="col-md-4">
								<input type="text" name="entry_remarks" id="entry_remarks" class="form-control" placeholder="Remarks" value="<?php echo $entry_remarks; ?>">
							</div>
						</div>
					</div>
					<div class="form-group offset-3">
						<input type="submit" value="Update" name="update" class="btn btn-success btn-lg">
						<a href="select.php" class="btn btn-primary btn-lg">Select</a>

					</div>
				</form>
				<?php else: ?>
				<form action="#" method="post">
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<input type="text" name="vch_no" id="vch_no" placeholder="Voucher No." class="form-control">
							</div>
							<div class="col-md-4">
								<input type="date" name="entry_date" id="entry_date" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<input type="text" name="entry_name" id="entry_name" class="form-control" placeholder="Name">
							</div>
							<div class="col-md-2">
								<input type="number" name="entry_qty" id="entry_qty" oninput="multipy();" class="form-control" placeholder="Quantity">
							</div>
							<div class="col-md-2">								
								<input type="number" name="entry_rate" oninput="multipy();" id="entry_rate" class="form-control" placeholder="Rate">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-4">
								<input type="number" name="entry_amt" id="entry_amt" class="form-control" placeholder="Amount">
							</div>
							<div class="col-md-4">
								<input type="text" name="entry_remarks" id="entry_remarks" class="form-control" placeholder="Remarks">
							</div>
						</div>
					</div>
					<div class="form-group offset-1">
						<input type="submit" value="Save" name="save" class="btn btn-success btn-lg">
						<a href="select.php" class="btn btn-primary btn-lg">Select</a>
						<a href="?update=yes&id=" class="btn btn-primary btn-lg disabled">Update</a>
						<input type="reset" value="Clear Field" class="btn btn-danger btn-lg">

					</div>
				</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<footer class="app_footer fixed-bottom">
		<div class="container">
			<div class="row">
				<div class="copyright">&copy; Surajit Basak</div>
			</div>
		</div>
	</footer>
	<script src="assets/jquery-3.3.1.min.js"></script>
	<script src="assets/bootstrap.min.js"></script>
	<script>
		function multipy()
		{
			var rate = document.getElementById('entry_rate').value;
			var qty = document.getElementById('entry_qty').value;
			document.getElementById('entry_amt').value = rate * qty;
		}
	</script>
</body>
</html>