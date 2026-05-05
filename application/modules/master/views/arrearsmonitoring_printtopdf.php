<html>
<head><meta charset="utf-8"><title></title></head>
<body onload="window.print()">
<div style="text-align:center;"><img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px"/></div>
<h3 style="text-align:center;">ARREARS MONITORING REPORT</h3>
<h6 style="text-align:center;"><?php echo 'As of '.$asofdate; ?></h6>
<table class="table" width="100%" border="1" cellspacing="0" cellpadding="4" style="font-size:12px;">
    <thead>
        <tr>
            <th>SN #</th><th>Cust Acct No.</th><th>Concessionaires</th><th>Meter Number</th><th>Zone</th>
            <th style="text-align:right;">Aging Amount</th><th style="text-align:right;">Current Billing Period Arrears</th>
        </tr>
    </thead>
    <tbody>
    <?php $idx=0; $gA=0; $gC=0; if(count($zone)>0){ foreach($zone as $z){ ?>
        <tr><td></td><td><b><?php echo stripslashes($z['zone']); ?></b></td><td colspan="5"></td></tr>
        <?php $rows = $this->report_model->get_arrears_monitoring_records($asofdate, $z['id'], $status); $zA=0; $zC=0;
        if(is_array($rows)){ foreach($rows as $r){ $idx++; $a=(float)(isset($r['total_balance'])?$r['total_balance']:0); $c=(float)(isset($r['current_arrears'])?$r['current_arrears']:0); ?>
            <tr>
                <td><?php echo $idx; ?></td>
                <td><?php echo $r['customer_id']; ?></td>
                <td><?php echo $r['last_name'].', '.$r['first_name'].' '.$r['middle_name']; ?></td>
                <td><?php echo $r['meter_number']; ?></td>
                <td><?php echo $r['zone']; ?></td>
                <td style="text-align:right;"><?php echo number_format($a,2); ?></td>
                <td style="text-align:right;"><?php echo number_format($c,2); ?></td>
            </tr>
        <?php $zA+=$a; $zC+=$c; $gA+=$a; $gC+=$c; }} ?>
        <tr><th colspan="5" style="text-align:right;">TOTAL</th><th style="text-align:right;"><?php echo number_format($zA,2); ?></th><th style="text-align:right;"><?php echo number_format($zC,2); ?></th></tr>
    <?php }} ?>
    <tr><th colspan="5" style="text-align:right;">GRAND TOTAL</th><th style="text-align:right;"><?php echo number_format($gA,2); ?></th><th style="text-align:right;"><?php echo number_format($gC,2); ?></th></tr>
    </tbody>
</table>
</body>
</html>
