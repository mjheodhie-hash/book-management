document.addEventListener('DOMContentLoaded', () => {
    const API_URL = '/api/books';

    // DOM Elements
    const booksTableBody = document.getElementById('books-table-body');
    const emptyState = document.getElementById('empty-state');
    const searchInput = document.getElementById('search-input');
    
    // Modal Elements
    const bookModal = document.getElementById('book-modal');
    const bookForm = document.getElementById('book-form');
    const modalTitle = document.getElementById('modal-title');
    const openAddModalBtn = document.getElementById('open-add-modal-btn');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelModalBtn = document.getElementById('cancel-modal-btn');
    
    // Form Inputs
    const bookIdInput = document.getElementById('book-id');
    const titleInput = document.getElementById('title');
    const authorInput = document.getElementById('author');
    const genreInput = document.getElementById('genre');
    const yearInput = document.getElementById('year_published');

    // Load Initial Books
    fetchBooks();

    // Event Listeners
    openAddModalBtn.addEventListener('click', () => openModal('add'));
    closeModalBtn.addEventListener('click', closeModal);
    cancelModalBtn.addEventListener('click', closeModal);
    bookForm.addEventListener('submit', handleFormSubmit);

    // Live Search Event Listener
    let searchDebounce = null;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => {
            fetchBooks(e.target.value.trim());
        }, 300);
    });

    // Fetch Books from API (TC-06, TC-15, TC-16, TC-17, TC-18)
    async function fetchBooks(query = '') {
        try {
            const endpoint = query ? `${API_URL}?search=${encodeURIComponent(query)}` : API_URL;
            const res = await fetch(endpoint);
            const data = await res.json();

            renderTable(data);
        } catch (err) {
            showToast('Failed to fetch books from server', 'error');
        }
    }

    // Render Books Table Body
    function renderTable(books) {
        booksTableBody.innerHTML = '';

        if (!Array.isArray(books) || books.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');

        books.forEach((book, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${index + 1}</td>
                <td><strong>${escapeHtml(book.title)}</strong></td>
                <td>${escapeHtml(book.author)}</td>
                <td>${escapeHtml(book.genre)}</td>
                <td>${book.year_published}</td>
                <td class="text-center">
                    <div class="action-btns">
                        <button class="btn-icon edit-btn" onclick="editBook(${book.id})" title="Edit Book">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn-icon delete-btn" onclick="deleteBook(${book.id})" title="Delete Book">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            booksTableBody.appendChild(tr);
        });
    }

    // Handle Create / Update Form Submission (TC-01, TC-09, TC-19, TC-20)
    async function handleFormSubmit(e) {
        e.preventDefault();
        clearErrors();

        const id = bookIdInput.value;
        const isEdit = Boolean(id);

        const payload = {
            title: titleInput.value.trim(),
            author: authorInput.value.trim(),
            genre: genreInput.value.trim(),
            year_published: yearInput.value.trim()
        };

        const url = isEdit ? `${API_URL}/${id}` : API_URL;
        const method = isEdit ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (res.status === 422) {
                // Display Validation Errors (TC-02, TC-03, TC-04, TC-05, TC-10)
                displayErrors(data.errors);
                return;
            }

            if (!res.ok) {
                showToast(data.message || 'An error occurred.', 'error');
                return;
            }

            showToast(data.message || (isEdit ? 'Book updated!' : 'Book created!'));
            closeModal();
            fetchBooks(searchInput.value.trim());

        } catch (err) {
            showToast('Unable to connect to server.', 'error');
        }
    }

    // Edit Book Action (TC-07)
    window.editBook = async function(id) {
        try {
            const res = await fetch(`${API_URL}/${id}`);
            if (!res.ok) {
                showToast('Book record not found', 'error');
                return;
            }
            const book = await res.json();

            bookIdInput.value = book.id;
            titleInput.value = book.title;
            authorInput.value = book.author;
            genreInput.value = book.genre;
            yearInput.value = book.year_published;

            openModal('edit');
        } catch (err) {
            showToast('Failed to fetch book details', 'error');
        }
    };

    // Delete Book Action (TC-12, TC-21)
    window.deleteBook = async function(id) {
        if (!confirm('Are you sure you want to permanently remove this book?')) return;

        try {
            const res = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' }
            });

            const data = await res.json();

            if (res.ok) {
                showToast(data.message || 'Book deleted successfully.');
                fetchBooks(searchInput.value.trim());
            } else {
                showToast(data.message || 'Could not delete book.', 'error');
            }
        } catch (err) {
            showToast('Error sending delete request.', 'error');
        }
    };

    // Modal Control Helper Functions
    function openModal(mode = 'add') {
        clearErrors();
        if (mode === 'add') {
            bookForm.reset();
            bookIdInput.value = '';
            modalTitle.textContent = 'Add New Book';
        } else {
            modalTitle.textContent = 'Edit Book Details';
        }
        bookModal.classList.remove('hidden');
    }

    function closeModal() {
        bookModal.classList.add('hidden');
        bookForm.reset();
        clearErrors();
    }

    function clearErrors() {
        document.querySelectorAll('.error-text').forEach(el => el.textContent = '');
    }

    function displayErrors(errors) {
        if (!errors) return;
        for (const field in errors) {
            const errorElement = document.getElementById(`error-${field}`);
            if (errorElement) {
                errorElement.textContent = errors[field][0];
            }
        }
    }

    // Floating Notification Toast Function
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type === 'error' ? 'error' : ''}`;
        toast.innerHTML = `
            <i class="fa-solid ${type === 'error' ? 'fa-triangle-exclamation' : 'fa-circle-check'}"></i>
            <span>${escapeHtml(message)}</span>
        `;
        container.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3500);
    }

    function escapeHtml(str) {
        return str.replace(/[&<>"']/g, match => {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };
            return map[match];
        });
    }
});