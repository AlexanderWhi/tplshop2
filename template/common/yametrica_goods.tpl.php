<script type="text/javascript">
window.dataLayer = window.dataLayer || [];
dataLayer.push({
    "ecommerce": {
        "detail": {
            "products": [
            {
            	"id":<?=$id?>,
            	"name":<?=printJSON($data['name'])?>,
            	"price":<?=$price?>
            }
            ]
        }
    }
});

</script>