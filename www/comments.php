<?php include('menu.php'); ?>


<!DOCTYPE html>
<html>
<head>
  <title>My Website - Comments</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      var comments = <?php echo file_get_contents('comments.json'); ?>;
      var currentComment = 0;

      // Display the first comment
      displayComment();

      // Cycle through the comments
      setInterval(function() {
        currentComment++;
        if (currentComment >= comments.length) {
          currentComment = 0;
        }
        displayComment();
      }, 5000);

      // Display the current comment
      function displayComment() {
        var comment = comments[currentComment];
        var html = '<div class="comment">';
        html += '<h3>' + comment.name + '</h3>';
        html += '<p class="email">' + comment.email + '</p>';
        html += '<p class="timestamp">' + new Date(comment.timestamp * 1000).toLocaleString() + '</p>';
        html += '<p class="comment-text">' + comment.comment + '</p>';
        html += '</div>';
        $('#comment-container').html(html);
      }
    });
  </script>
</head>
<body>
  <header>
    <div class="container">
      <h1>My Website</h1>
    </div>
  </header>
  <main>
    <div class="container">
      <h2>Leave a Comment</h2>
      <form action="submit-comment.php" method="POST">
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="comment">Comment:</label>
          <textarea id="comment" name="comment" required></textarea>
        </div>
        <button type="submit" class="btn">Submit</button>
      </form>

      <h2>Comments</h2>
      <div id="comment-container"></div>
    </div>
  </main>
  <footer>
    <div class="container">
      <p>&copy; 2023 My Website. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
