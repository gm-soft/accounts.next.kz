
    <?php 
    if (DEBUG == true){
        ?>
        <p>
            <pre>
                <?= var_export($_COOKIE, true) ?> <br>
                <?= var_export($_SERVER, true) ?>
            </pre>
        </p>
    <?php  } ?>
    

    <footer class="footer">
        <div class="container">
            
        </div>

    </footer>


  </body>
</html>