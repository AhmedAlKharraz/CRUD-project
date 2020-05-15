<?php
require 'inc/functions.php';

$page = "reports";
$pageTitle = "Reports | Time Tracker";
$filter = "all";

if(!empty($_GET['filter'])){
    $filter = explode(':', filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_STRING));
}

var_dump($filter);

include 'inc/header.php';
?>
<div class="col-container page-container">
    <div class="col col-70-md col-60-lg col-center">
        <div class="col-container">
            <h1 class='actions-header'>Reports</h1>
            <form class='form-container form-report' action='reports.php' method='get'>
                <label for='filter'>Filter:</label>
                <select id='filter' name='filter'>
                    <option value=''>Select One</option>
                    <optgroup label="Project">
                    <?php
                        foreach(get_project_list() as $item){
                            echo "<option value='project:".$item['project_id']."'>";
                            echo $item['title']."</option>\n";
                        }
                    ?>
                    </optgroup>
                    <optgroup label="Category">
                        <option value="category:billable">Billable</option>
                        <option value="category:charity">Charity</option>
                        <option value="category:personal">Personal</option>
                    </optgroup>
                </select>
                <input class='button' type='submit' value='run' />
            </form>
        </div>
        <div class="section page">
            <div class="wrapper">
                <table>
                    <?php 
                        $total = $project_id = $project_total = 0;
                        $tasks = get_task_list($filter);
                        foreach($tasks as $item){
                            //The variable $project_id starts off at 0. The first time the loop runs $project_id will not be equal to the first $item's 'project_id' property and the loop will create the header row. Then it will add the task information for that project. When the loop runs again, if the next $item's 'project_id' is still the same as the $project_id variable that was set in the previous iteration of the loop, the next task for that project will be added. If the next $item's 'project_id' property is NOT the same as the $project_id variable, the code to create a new header will run and then the code to add the a task for the new project will run
                            if($project_id != $item['project_id']){
                                
                                $project_id = $item['project_id'];
                                echo "<thead>\n";
                                echo "<tr>\n";
                                echo "<th>".$item['project']."</th>\n";
                                echo "<th> Date </th>\n";
                                echo "<th> Time </th>\n";
                                echo "</tr>\n";
                                echo "</thead>\n";
                            }
                            $project_total += $item['time'];
                            $total += $item['time'];
                            echo "<tr>\n";
                            echo "<td>".$item['title']."</td>\n";
                            echo "<td>".$item['date']."</td>\n";
                            echo "<td>".$item['time']."</td>\n";
                            echo "</tr>\n";

                            //From CRUD Operations with PHP, Summarizing Project Time lesson
                            if(next($tasks)['project_id'] != $item['project_id']){
                                echo "<tr>\n";
                                echo "<th class='project-total-number' colspan='2'> Project total </th>\n";
                                echo "<th class='project-total-number'> $project_total </th>\n";
                                echo "</tr>\n";
                                $project_total = 0;
                            }
                        }
                    ?>
                    <tr>
                        <th class='grand-total-label' colspan='2'>Grand Total</th>
                        <th class='grand-total-number'><?php echo $total ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "inc/footer.php"; ?>

