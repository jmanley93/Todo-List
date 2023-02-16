# Todo-List
Wordpress Vue plugin for a simple todo list

Adds a simple UI for a todo list. Includes displaying a list of previous todo items, and a form to add new items. Items require a text description, a date, and whether or not the task is complete. Adds a custom post type of 'todo' with custom fields 'tododescription', 'tododate', and 'todostatus'. 

Form uses basic HTML controls with custom styling. Fields should be in a logical tab order, and focus moves back to the first field on submission so you can easily fill it out again. Basic validation ensures that the date and description cannot be blank. 

To install, clone repository into your app\public\wp-content\plugins folder. 
To display, add a [todoList] shortcode to a post.
Everything else should work out of the box. 
