C:.
│   about.php                  # About page
│   delete_recipe.php          # Script to delete a recipe
│   edit_recipe.php            # Script to edit a recipe
│   favorite.php               # Add/remove recipes from favorites
│   feedback.php               # Submit user feedback
│   group_chat.php             # Group chat interface
│   home.php                   # Homepage displaying recipes
│   index.php                  # Entry point for the application
│   like.php                   # Like/unlike a recipe
│   login.php                  # User login functionality
│   logout.php                 # User logout functionality
│   manage_uploads.php         # Manage uploaded recipes
│   profile.php                # User profile page
│   README.md                  # This user manual
│   register.php               # User registration functionality
│   show_favorite.php          # Display user's favorite recipes
│   updateProfile.php          # Update user profile details
│   upload.php                 # Upload a new recipe
│   upload_process.php         # Process uploaded recipe data
│
├───assets                     # Static assets (e.g., images)
│   └───images                 # Default and user-uploaded images
│
├───chat1                      # WebSocket-based group chat module
│   │   chat_logs.json         # JSON file storing chat logs
│   │   chat_logs.txt          # Backup text file for chat logs
│   │   composer.json          # Composer configuration for dependencies
│   │   composer.lock          # Lock file for installed dependencies
│   │
│   ├───bin                    # Executable scripts
│   │       chat_server.php    # WebSocket server script
│   │
│   ├───src                    # Custom classes for chat functionality
│   │       chat.php           # Chat logic implementation
│   │
│   └───vendor                 # Installed dependencies (via Composer)
│       │   autoload.php       # Autoloader for dependencies
│
├───css                        # Stylesheets
│       styles.css             # Main CSS file for styling
│
├───imgs                       # Recipe-related images
│   └───recipe_images          # Uploaded recipe images
│
├───includes                   # Shared PHP files
│       db.php                 # Database connection script
│       footer.php             # Footer template
│       header.php             # Header template
│
├───js                         # JavaScript files
│       scripts.js             # Client-side scripts
│
└───sql                        # SQL files
        SizzleShare.sql        # Database schema and initial data

        profile


Prerequisites
Before setting up the project, ensure you have the following installed:

XAMPP/WAMP : For running Apache and MySQL.
PHP : Version 7.4 or higher.
Composer : For managing PHP dependencies.
MySQL : To store user data, recipes, and feedback.
Browser : Modern browsers like Chrome, Firefox, or Edge.