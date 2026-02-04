
<style>
#close_window {
    position: fixed;
    right: 20px;
    background-color: lightgray;
    padding: 15px;
    font-family: Arial, Helvetica, sans-serif;
   }

#close_window::after {
     content: "Sluit venster";
}

@media (max-width: 500px) {

 #close_window::after {
     content: "X";
}
}

</style>

<?php

echo '<div id="close_window"> </div>';

?>

<script defer>
    document.getElementById("close_window").addEventListener('click', () => {
        window.close();
    })
</script>
