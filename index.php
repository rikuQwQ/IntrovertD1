<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<body>
	<form action="getClientsBudget.php">
		<input type="date" name="dateFrom" max="<?= date('Y-m-d'); ?>">
		<input type="date" name="dateTo" max="<?= date('Y-m-d'); ?>">
		<button type="submit">Найти</button>
	</form>
</body>

</html>

<html>
<style>
	table {
		width: 100%;
		margin-bottom: 20px;
		border: 1px solid #000;
		border-collapse: collapse;
	}

	th {
		font-weight: bold;
		padding: 5px;
		background: #efefef;
		border: 1px solid #000;
	}

	td {
		border: 1px solid #000;
		padding: 5px;
		text-align: center;
	}
</style>

</html>