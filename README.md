# AI-website-Development
My usage of artificial intelligence to create a fully functional website, with as little editing from humans as possible.
The goal is to see if I (who knows very little about coding) can talk AI through creating a website using only prompts. 

After the website is able to be safely put into production, I hope to get a 'red team' together to test the vulnerability of an AI created website, allowing people to essentially hack into the website.

## Version 0.1 March 21, 2023

* We started by setting up the basic file structure for the web application, including a config.php file for database connection details and other global settings.

* We created the login functionality using PHP and MySQL. Users can enter their username and password to log in, and if their credentials are correct, they are redirected to the index.php page.

* We created a menu.php file that is included on all pages of the site, allowing users to easily navigate between pages.

* We created a logout.php file that destroys the session and redirects the user to the login page.

* We created the ability for users to create new groups and join existing groups. Groups are stored in a MySQL database table called my_groups, and group membership is stored in a table called group_members.

* We added functionality to display the newest 3 created groups on the index.php page, including the date the group was created and the number of members in the group.

* We added functionality to display all groups that the user is a member of on the groups.php page, allowing users to easily access their groups and view group content.

Overall, we've created a basic social media site that allows users to create and join groups, post content, and interact with other users. Of course, there is always room for improvement and further development, but we've established a strong foundation for a functional and user-friendly web application.

## Version 0.1a March 22, 2023
* We added a conditional statement to the "profile.php" page to check if the logged-in user is already friends with the user whose profile is being viewed, and display a button to send a friend request if they are not already friends.
* We added a new column called "status" to the "friendships" table to store the status of the friendship.* We created the logic to update the "friendships" table when a user accepts a friend request on the "accept-friends.php" page.
* We identified and fixed a number of syntax errors in the code for sending and accepting friend requests, including issues with variable names, missing semicolons, and incorrect SQL queries.
* We rewrote the code for accepting friend requests from scratch to ensure that the user's friend list is updated properly when a request is accepted.
* We added a feature to profile.php that changes the has_pending_requests column in the users table from 0 to 1 when someone clicks the "Add friend" button.
* We modified the logic for displaying the "Add friend" button on profile.php to only show the button if the logged-in user is not already friends with the user whose profile is being viewed.
* We added some error-handling code to prevent the user from trying to accept or send friend requests if the request does not exist or if the user does not have permission to make the request.
* We reviewed the code for profile.php and made some changes to improve its readability and maintainability.
