<?php
session_start();

include_once 'include/config.php';
if(strlen($_SESSION['alogin'])==0)
  { 
header('location:index.php');
}
else{
$oid=intval($_GET['oid']);
if(isset($_POST['submit2'])){
$status=$_POST['status'];
$remark=$_POST['remark'];//space char

$query=mysqli_query($con,"insert into ordertrackhistory(orderId,status,remark) values('$oid','$status','$remark')");
$sql=mysqli_query($con,"update orders set orderStatus='$status' where id='$oid'");
echo "<script>alert('Order updated sucessfully...');</script>";
//}
}

 ?>
<script language="javascript" type="text/javascript">
function f2()
{
window.close();
}ser
function f3()
{
window.print(); 
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Update Compliant</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="anuj.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php include('include/header.php');?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <?php include('include/sidebar.php');?>
            <div class="span9">
                <div class="content">
                    <div class="module">
                        <div class="module-head">
                            <h3>Update Order #<?php echo $oid; ?></h3>
                        </div>
                        <div class="module-body table">
                            <div class="table-responsive" style="margin-top: 30px;">
                                <form name="updateticket" id="updateticket" method="post">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <th colspan="2" style="text-align:center; font-size:18px;">Update Order</th>
                                            </tr>
                                            <tr>
                                                <th>Order Id:</th>
                                                <td><?php echo $oid; ?></td>
                                            </tr>
                                            <?php 
                                            $ret = mysqli_query($con,"SELECT * FROM ordertrackhistory WHERE orderId='$oid'");
                                            while($row=mysqli_fetch_array($ret)) {
                                            ?>
                                            <tr>
                                                <th>At Date:</th>
                                                <td><?php echo $row['postingDate']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Status:</th>
                                                <td><?php echo $row['status']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Remark:</th>
                                                <td><?php echo $row['remark']; ?></td>
                                            </tr>
                                            <tr><td colspan="2"><hr /></td></tr>
                                            <?php } ?>
                                            <?php 
                                            $st='Delivered';
                                            $rt = mysqli_query($con,"SELECT * FROM orders WHERE id='$oid'");
                                            while($num=mysqli_fetch_array($rt)) {
                                                $currrentSt=$num['orderStatus'];
                                            }
                                            if($st==$currrentSt) { ?>
                                            <tr><td colspan="2"><b>Product Delivered</b></td></tr>
                                            <?php } else { ?>
                                            <tr>
                                                <th>Status:</th>
                                                <td>
                                                    <select name="status" class="form-control" required>
                                                        <option value="">Select Status</option>
                                                        <option value="in Process">In Process</option>
                                                        <option value="Delivered">Delivered</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Remark:</th>
                                                <td><textarea cols="50" rows="4" name="remark" class="form-control" required></textarea></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <input type="submit" name="submit2" value="Update" class="btn btn-primary" style="margin-right:10px;" />
                                                    <button type="button" class="btn btn-danger" onclick="window.close();">Close this Window</button>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/theme.css">
<link rel="stylesheet" href="images/icons/css/font-awesome.css">
<script src="scripts/jquery-1.9.1.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="scripts/datatables/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        // Only initialize DataTables on actual tables, not divs
        $('table.table').dataTable();
    });
</script>
</body>
</html>
<?php } ?>

