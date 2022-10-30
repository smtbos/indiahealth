<?php
if ((!isset($cascad)) && count($_POST) == 0 && isset($_SESSION["amsg"]) && count($_SESSION["amsg"]) > 0) {
    foreach ($_SESSION["amsg"] as $key => $value) {
        $_SESSION["amsg"][$key] = "<b>" . $value . "</b>";
    }
    $s = implode('<br>', $_SESSION["amsg"]);
    unset($_SESSION["amsg"]);
?>
    <script>
        $(document).ready(function() {
            $.confirm({
                backgroundDismiss: true,
                icon: 'fa fa-exclamation-circle',
                title: '<b>Alert!</b>',
                content: '<?php echo $s; ?>',
                type: 'red',
                buttons: {
                    ok: {
                        keys: ['enter'],
                    },
                }
            });
        });
    </script>
<?php
}
?>
<?php
if ((!isset($cascad)) && count($_POST) == 0 && isset($_SESSION["smsg"]) && count($_SESSION["smsg"]) > 0) {
    foreach ($_SESSION["smsg"] as $key => $value) {
        $_SESSION["smsg"][$key] = "<b>" . $value . "</b>";
    }
    $s = implode('<br>', $_SESSION["smsg"]);
    unset($_SESSION["smsg"]);
?>
    <script>
        $(document).ready(function() {
            $.confirm({
                backgroundDismiss: true,
                icon: 'fa fa-check-circle',
                title: '<b>Success!</b>',
                content: '<?php echo $s; ?>',
                type: 'green',
                buttons: {
                    ok: {
                        keys: ['enter'],
                    },
                }
            });
        });
    </script>
<?php
}
?>