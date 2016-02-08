<script type="text/javascript">
<?/*var yaParams = <?=printJSON($data['metrica'])?>;*/?>

window.dataLayer = window.dataLayer || [];
dataLayer.push({
    "ecommerce": {
        "purchase": {
            "actionField": {
                "id" : "<?=$data['metrica']['order_id']?>"
            },
            "products": <?=printJSON($data['metrica']['goods'])?>
        }
    }
});

</script>