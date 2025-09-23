<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <h1>This is my fist PHP file</h1>
        <table border="1">
         <?php
         for ($i = 0; $i < 5; $i++) {
            echo "
            <tr>
                <td>$i</td>
            </tr>";
         }
         ?>
        </table>
    </body>
</html>