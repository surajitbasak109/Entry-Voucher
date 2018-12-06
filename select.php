<?php

require_once 'config/db.php';

$query = $pdo->prepare("SElECT * FROM entries");
$query->execute();

$all_entries = $query->fetchAll();

// need to delete
if(isset($_GET['delete']) && !empty($_GET['id']))
{
	$q = $pdo->prepare("DELETE FROM `entries` WHERE entry_id = :id");
	if ( $q->execute(array(":id"=> $_GET['id'])) )
	{
		$errorMsg = "Successfully deleted.";
		header('location: select.php');
	}
	else
	{
		$errorMsg = "Database error occurred.";
	}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Showing All entries</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="main.css">
</head>
<body>
	<header class="app_header">
		<h2 class="text-center">Showing All entries</h2>
		<div class="text-center">
			<p class="text-danger"><?php if (isset($errorMsg)) { echo $errorMsg; } ?></p>
		</div>
	</header>
	<div class="app_main">
		<div class="container">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Sr. No.</th>
						<th>Voucher No.</th>
						<th>Date</th>
						<th>Name</th>
						<th>Quantity</th>
						<th>Rate</th>
						<th>Amount</th>
						<th>Remarks</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
<?php
$count = 0;
foreach ($all_entries as $k):
$count++;
?>
<tr>
	<td><?php echo $count; ?></td>
	<td><?php echo $k['entry_vch_no']; ?></td>
	<td><?php echo date('d M, Y', strtotime(str_replace('-', '/', $k['entry_date']))); ?></td>
	<td><?php echo $k['entry_name']; ?></td>
	<td><?php echo $k['entry_qty']; ?></td>
	<td><?php echo $k['entry_rate']; ?></td>
	<td><?php echo $k['entry_amt']; ?></td>
	<td><?php echo $k['entry_remarks']; ?></td>
	<td>
		<a href="index.php?edit=yes&id=<?php echo $k['entry_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
		<a href="?delete=yes&id=<?php echo $k['entry_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
	</td>
</tr>
<?php endforeach; ?>
				</tbody>
			</table>
			<div class="text-center"><a class="btn btn-primary" href="index.php">Back to homepage</a></div>
		</div>
	</div>
	<footer class="app_footer fixed-bottom">
		<div class="container">
			<div class="row">
				<div class="copyright">&copy; Surajit Basak</div>
			</div>
		</div>
	</footer>
</body>
</html>