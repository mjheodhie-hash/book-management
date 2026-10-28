<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management System</title>
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="dark-theme">

    <!-- Floating Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

    <div class="app-container">
        <!-- Top Navigation Header -->
        <header class="app-header">
            <div class="brand">
                <i class="fa-solid fa-book-bookmark"></i>
                <div>
                    <h2>LibroSync</h2>
                    <p class="subtitle">Digital Book Collection System</p>
                </div>
            </div>
            
            <!-- Dark / Light Theme Switch Button -->
            <button class="theme-toggle-btn neumorphic-btn" id="theme-toggle-btn" title="Toggle Theme">
                <i class="fa-solid fa-moon" id="theme-icon"></i>
                <span id="theme-label">Dark Mode</span>
            </button>
        </header>

        <!-- Main Workspace -->
        <main class="app-main">
            <!-- Controls Bar: Search & Action Buttons -->
            <section class="controls-card neumorphic-card">
                <div class="search-box neumorphic-inset">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input type="text" id="search-input" placeholder="Search by title or author..." autocomplete="off">
                </div>
                <button class="btn btn-primary neumorphic-btn" id="open-add-modal-btn">
                    <i class="fa-solid fa-plus"></i> Add New Book
                </button>
            </section>

            <!-- Book List Table Container -->
            <section class="table-card neumorphic-card">
                <div class="table-wrapper">
                    <table class="neumorphic-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Year</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="books-table-body">
                            <!-- Dynamically loaded via app.js -->
                        </tbody>
                    </table>
                </div>
                <div id="empty-state" class="empty-state hidden">
                    <i class="fa-solid fa-box-open"></i>
                    <p>No book records found.</p>
                </div>
            </section>
        </main>
    </div>

    <!-- Neumorphic Modal Dialog for Create & Edit -->
    <div class="modal-overlay hidden" id="book-modal">
        <div class="modal-card neumorphic-card">
            <div class="modal-header">
                <h3 id="modal-title">Add New Book</h3>
                <button class="btn-close" id="close-modal-btn">&times;</button>
            </div>
            <form id="book-form">
                <input type="hidden" id="book-id">

                <div class="form-group">
                    <label for="title">Book Title</label>
                    <div class="input-wrapper neumorphic-inset">
                        <i class="fa-solid fa-heading"></i>
                        <input type="text" id="title" placeholder="e.g. To Kill a Mockingbird">
                    </div>
                    <span class="error-text" id="error-title"></span>
                </div>

                <div class="form-group">
                    <label for="author">Author</label>
                    <div class="input-wrapper neumorphic-inset">
                        <i class="fa-solid fa-user-pen"></i>
                        <input type="text" id="author" placeholder="e.g. Harper Lee">
                    </div>
                    <span class="error-text" id="error-author"></span>
                </div>

                <div class="form-group">
                    <label for="genre">Genre</label>
                    <div class="input-wrapper neumorphic-inset">
                        <i class="fa-solid fa-layer-group"></i>
                        <input type="text" id="genre" placeholder="e.g. Fiction / Classic">
                    </div>
                    <span class="error-text" id="error-genre"></span>
                </div>

                <div class="form-group">
                    <label for="year_published">Year Published</label>
                    <div class="input-wrapper neumorphic-inset">
                        <i class="fa-solid fa-calendar"></i>
                        <input type="number" id="year_published" placeholder="e.g. 1960">
                    </div>
                    <span class="error-text" id="error-year_published"></span>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary neumorphic-btn" id="cancel-modal-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary neumorphic-btn" id="save-book-btn">Save Book</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>