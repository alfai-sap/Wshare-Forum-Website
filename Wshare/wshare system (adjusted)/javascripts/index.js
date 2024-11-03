
        

        //hide and unhide mga comments
        document.addEventListener("DOMContentLoaded", function() {
            const commentHeaders = document.querySelectorAll(".comm_label");
                commentHeaders.forEach(header => {
                    header.addEventListener("click", function() {
                        const comments = this.parentNode.querySelectorAll(".post-comments");
                            comments.forEach(comment => {
                                comment.style.display = (comment.style.display === "none" || comment.style.display === "") ? "block" : "none";
                    });
                });
            });
        });


        //ihide and unhide ang author, content and timestamp
        document.addEventListener("DOMContentLoaded", function() {

            const postcontents = document.querySelectorAll(".post h3");

            postcontents.forEach(header => {

                header.addEventListener("click", function() {

                    const contents = this.parentNode.querySelectorAll(".post_content");

                    contents.forEach(content => {

                        content.style.display = (content.style.display === "none" || content.style.display === "") ? "block" : "none";
                        

                    });

                });

            });

        });

  
    



    
            //di nagamit
            function editPost(postId) {;
                window.location.href = "edit_post.php?id=" + postId;;
            };
            function deletePost(postId) {;
                if (confirm("Are you sure you want to delete this post?")) {;
                    window.location.href = "delete_post.php?id=" + postId;;
                };
            };





    $(document).ready(function() {
        // Handle form submission for creating posts
        $("#post-form").submit(function(event) {
            // Prevent the default form submission
            event.preventDefault();
            
            // Serialize form data
            var formData = $(this).serialize();

            // Send an AJAX request to the server
            $.ajax({
                type: "POST",
                url: "create_post_process.php",
                data: formData,
                success: function(response) {
                    // Update the content dynamically with the response
                    // For example, you can show a success message or update the post list
                    // Reload the page or update the content as needed
                    location.reload(); // Example: Reload the page
                },
                error: function(xhr, status, error) {
                    // Handle errors if any
                    console.error(xhr.responseText);
                    alert("Error creating post. Please try again later.");
                }
            });
        });

        // Handle form submission for creating comments
        $(".comment-form").submit(function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Serialize form data
            var formData = $(this).serialize();

            // Send an AJAX request to the server
            $.ajax({
                type: "POST",
                url: "comment_process.php",
                data: formData,
                success: function(response) {
                    // Update the content dynamically with the response
                    // For example, you can show a success message or update the comment section
                    // Reload the page or update the content as needed
                    location.reload(); // Example: Reload the page
                },
                error: function(xhr, status, error) {
                    // Handle errors if any
                    console.error(xhr.responseText);
                    alert("Error adding comment. Please try again later.");
                }
            });
        });
    });



    function sortPosts(sortOption) {
    // Make an AJAX request to the server
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Update the content dynamically with the response
                var postsContainer = document.getElementById('posts-container');
                postsContainer.innerHTML = xhr.responseText;
            } else {
                console.error('Error:', xhr.status);
            }
        }
    };

    // Prepare the request URL based on the selected sorting option
    var url;
    if (sortOption === 'time') {
        url = 'functions.php?sort=time';
    } else if (sortOption === 'date') {
        url = 'functions.php?sort=date';
    } else if (sortOption === 'comments') {
        url = 'functions.php?sort=comments';
    }

    // Send the AJAX request
    xhr.open('GET', url, true);
    xhr.send();
    }

    // Add event listeners to the sorting options
    document.getElementById('sort-by-time').addEventListener('click', function() {
        sortPosts('time');
    });

    document.getElementById('sort-by-date').addEventListener('click', function() {
        sortPosts('date');
    });

    document.getElementById('sort-by-comments').addEventListener('click', function() {
        sortPosts('comments');
    });







    $(document).ready(function() {
        // When the like button is clicked
        $('#like-btn').click(function() {
            var postID = $(this).data('PostID');

            // Send an AJAX request to like_post.php
            $.ajax({
                type: 'POST',
                url: 'like_post.php',
                data: { like: true, postID: postID },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Update the like count on success
                        $('#like-count').text(response.likeCount);
                    } else {
                        // Handle errors or display a message if necessary
                        console.error('Failed to like post.');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors or display a message if necessary
                    console.error('Error:', error);
                }
            });
        });
    });





    $(document).ready(function() {
        // When the like button is clicked
        $('#like-btn').click(function() {
            var postID = $(this).data('PostID');

            // Send an AJAX request to like_post.php
            $.ajax({
                type: 'POST',
                url: 'like_post.php',
                data: { like: true, postID: postID },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Update the like count on success
                        $('#like-count').text(response.likeCount);
                    } else {
                        // Handle errors or display a message if necessary
                        console.error('Failed to like post.');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors or display a message if necessary
                    console.error('Error:', error);
                }
            });
        });
    });
