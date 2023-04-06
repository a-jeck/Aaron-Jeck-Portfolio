<?php session_start(); ?>
<article>
    <div class="content">
        <h2> Recent Stories </h2>
        <?php
        require 'database.php';

        /// Prepare SQL query for post information
        $stories = $mysqli->prepare("select post_id, author_id, date, title, link from stories order by date desc;");
        if (!$stories) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        /// Execute and retrieve data from SQL query
        $stories->execute();
        $currentstory = $stories->get_result();

        /// Count how many stories there are and set up table for results
        $counter = 0;
        echo '<table>';

        /// Iterate through all stories
        while ($row = $currentstory->fetch_assoc()) {

            /// New row for each story            
            echo '<tr>';

            /// Retrieve the author's name from their ID
            $_SESSION['id_lookup'] = htmlentities($row["author_id"]);
            include('id_lookup.php');

            /// Get at most first 100 characters from title - we don't want to display too many
            $full_title = $row['title'];
            $truncated_title = substr($full_title, 0, 100);


            /// Print the title of the story with a hyperlink to it
            printf(
                '%s %s %s %s ',
                '<td> <p class="title">',
                
		'<a href="/portfolio/forum/story/' . $row["post_id"] . '" class="title-link">',
		htmlentities($truncated_title),
                '</a> </p>'
            );

            /// If the author included a link to a website, include it. 
            if (!empty($row['link'])) {

                /// Parse the URL to get domain.com & print
                $unedited_link = $row['link'];
                $edited_link = parse_url($unedited_link);
                $host =  $edited_link['host'];


                printf(
                    '%s %s %s %s%s%s',
                    '<p class="link">',
                    "<a href=",
                    htmlentities($unedited_link),
                    'class="links">(',
                    htmlentities($host),
                    ')</a> </p>'
                );
            }

            /// Finish the presentation by printing the author's full name & the date.
            $date = date("l, F j, Y", strtotime($row['date'])); 
            printf(
                '%s %s %s %s %s %s%s',
                '<br> <p class="authordate"> posted by ',
		'<a href="/portfolio/forum/profile/' . $row["author_id"] . '">',
		htmlentities($_SESSION['lookup_name_first']) . " " . htmlentities($_SESSION['lookup_name_last']),
                '</a>',
                'on',
                $date,
                '</p> </td>'
            );

            /// End the loop by adding one to the coutner and closing the tags
            $counter += 1;
            echo '</tr>';
        }

        /// Close the table and the SQL query
        echo '</table>';
        $stories->close();
        ?>
    </div>
</article>
