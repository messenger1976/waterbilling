<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SN#</th>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Meter Number</th>
                <th>Zone</th>
                <th>Aging Amount</th>
                <th>Current Billing Period Arrears</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $idx = 1; $totAging = 0; $totCur = 0; ?>
            <?php if (count($record) > 0) { foreach ($record as $row) { 
                $aging = isset($row['total_balance']) ? (float)$row['total_balance'] : 0;
                $cur = isset($row['current_arrears']) ? (float)$row['current_arrears'] : 0;
                $is_mismatch = abs($aging - $cur) > 0.009;
                $row_style = $is_mismatch ? 'background-color:#d9534f;color:#ffffff;' : '';
            ?>
            <tr style="<?php echo $row_style; ?>">
                <td><?php echo $idx++; ?></td>
                <td><?php echo stripslashes($row['customer_id']); ?></td>
                <td><?php echo stripslashes(trim($row['last_name']).', '.trim($row['first_name']).' '.trim($row['middle_name'])); ?></td>
                <td><?php echo stripslashes($row['meter_number']); ?></td>
                <td><?php echo stripslashes($row['zone']); ?></td>
                <td align="right"><?php echo number_format($aging, 2); ?></td>
                <td align="right" class="current-arrears-cell"><?php echo number_format($cur, 2); ?></td>
                <td align="center">
                    <?php if($is_mismatch){ ?>
                    <button
                        type="button"
                        class="btn btn-xs btn-danger btn-update-arrears"
                        data-customer-id="<?php echo htmlspecialchars($row['customer_id'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-aging-amount="<?php echo number_format($aging, 2, '.', ''); ?>"
                    >Update Arrears</button>
                    <?php } else { ?>
                    -
                    <?php } ?>
                </td>
            </tr>
            <?php $totAging += $aging; $totCur += $cur; } } ?>
            <tr>
                <th colspan="5" style="text-align:right">GRAND TOTAL</th>
                <th style="text-align:right"><?php echo number_format($totAging, 2); ?></th>
                <th style="text-align:right"><?php echo number_format($totCur, 2); ?></th>
                <th></th>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
$(document).off('click.arrearsupdate', '.btn-update-arrears').on('click.arrearsupdate', '.btn-update-arrears', function(){
    var $btn = $(this);
    var customerId = $btn.data('customer-id');
    var agingAmount = $btn.data('aging-amount');
    var $row = $btn.closest('tr');

    Swal.fire({
        title: "Update arrears?",
        text: "This will replace current billing period arrears with Aging Amount (" + parseFloat(agingAmount).toFixed(2) + ").",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d9534f",
        confirmButtonText: "Yes, update it",
        cancelButtonText: "Cancel"
    }).then(function(result){
        if(!result.isConfirmed){ return; }
        $.ajax({
            type: "POST",
            url: "<?php echo ADMIN_URL;?>reports/updatearrearsmonitoring",
            dataType: "json",
            data: { customer_id: customerId, aging_amount: agingAmount },
            success: function(resp){
                if(resp && resp.success){
                    $row.find('.current-arrears-cell').text(parseFloat(resp.current_arrears).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
                    $row.css({"background-color":"", "color":""});
                    $btn.closest('td').html('<span class="label label-success">Updated</span>');
                    Swal.fire({ icon: "success", title: "Updated!", text: "Current Billing Period Arrears was updated." });
                } else {
                    Swal.fire({ icon: "error", title: "Failed", text: resp && resp.message ? resp.message : "Unable to update arrears." });
                }
            },
            error: function(){
                Swal.fire({ icon: "error", title: "Failed", text: "Server error while updating arrears." });
            }
        });
    });
});
</script>
