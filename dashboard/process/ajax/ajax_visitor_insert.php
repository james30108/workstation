<?php include('../function.php'); 

// แสดงรหัสอัปไลน์ 
if (isset($_GET['direct_code'])) {

	$direct_code = $_GET['direct_code'];
	$query       = mysqli_query($connect, "SELECT * FROM system_member WHERE member_code = '$direct_code' ");
	$data        = mysqli_fetch_array($query);
	
	if (isset($data)) { ?>
		
		<input type="hidden" name="buyer_direct" value="<?php echo $data['member_id'] ?>">
        <input type="text" class="form-control" name="member_name" placeholder="ชื่อผู้แนะนำ" value="<?php echo $data['member_name'] ?>" required readonly>

	<?php } else { ?>

		<input type="hidden" name="buyer_direct" value="1">
        <input type="text" class="form-control" placeholder="กรุณากรอกรหัสผู้แนะนำให้ถูกต้อง" required onkeypress="return false;">

<?php } } ?>