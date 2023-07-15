@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <h3>Blog Data:</h3>
                    <ul id="blogList"></ul>
                    
                    <h3>Add New Blog:</h3>
                    <form id="addBlogForm">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="author">Author</label>
                            <input type="text" class="form-control" id="author" name="author" required>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="imgUrl">Image URL</label>
                            <input type="text" class="form-control" id="imgUrl" name="imgUrl" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Blog</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateBlogForm">
                    <div class="form-group">
                        <label for="updateTitle">Title</label>
                        <input type="text" class="form-control" id="updateTitle" name="updateTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="updateAuthor">Author</label>
                        <input type="text" class="form-control" id="updateAuthor" name="updateAuthor" required>
                    </div>
                    <div class="form-group">
                        <label for="updateContent">Content</label>
                        <textarea class="form-control" id="updateContent" name="updateContent" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="updateImgUrl">Image URL</label>
                        <input type="text" class="form-control" id="updateImgUrl" name="updateImgUrl" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
let blogs = [];

axios.get('/api/blogs')
  .then(response => {
    blogs = response.data;
    const blogList = document.getElementById('blogList');
    blogs.forEach(blog => {
      const { id, title, author, content, imgUrl } = blog;
      
      // Create list item for each blog
      const listItem = document.createElement('li');
      
      // Populate list item with blog data
      listItem.innerHTML = `
        <strong>Title:</strong> ${title}<br>
        <strong>Author:</strong> ${author}<br>
        <strong>Content:</strong> ${content}<br>
        <strong>Image:</strong> <img src="${imgUrl}" alt="Blog Image" style="width: 500px; height: 500px;"><br>
        <button type="button" class="btn btn-danger" onclick="deleteBlog(${id})">
          Delete
        </button>
        <button type="button" class="btn btn-primary" onclick="openUpdateModal(${id})">
          Update
        </button>
        <hr>
      `;
      
      // Add list item to blog list
      blogList.appendChild(listItem);
    });
  })
  .catch(error => {
    console.error(error);
  });

document.getElementById('addBlogForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const form = event.target;
    const title = form.elements.title.value;
    const author = form.elements.author.value;
    const content = form.elements.content.value;
    const imgUrl = form.elements.imgUrl.value;
    
    const newBlog = {
        title: title,
        author: author,
        content: content,
        imgUrl: imgUrl
    };
    
    axios.post('/api/blogs', newBlog)
        .then(response => {
            console.log('Blog added successfully:', response.data);
            // Clear form fields
            form.reset();
            // Refresh the blog list by fetching all blogs again
            refreshBlogList();
        })
        .catch(error => {
            console.error('Error adding blog:', error);
        });
});

function deleteBlog(id) {
  axios.delete(`/api/blogs/${id}`)
    .then(response => {
      console.log('Blog deleted successfully');
      // Refresh the blog list by fetching all blogs again
      refreshBlogList();
    })
    .catch(error => {
      console.error('Error deleting blog:', error);
    });
}

function openUpdateModal(id) {
    const updateModal = document.getElementById('updateModal');
    const updateForm = document.getElementById('updateBlogForm');
    const blog = blogs.find(blog => blog.id === id);

    updateForm.elements.updateTitle.value = blog.title;
    updateForm.elements.updateAuthor.value = blog.author;
    updateForm.elements.updateContent.value = blog.content;
    updateForm.elements.updateImgUrl.value = blog.imgUrl;

    updateModal.dataset.blogId = id;
    $(updateModal).modal('show');
}

document.getElementById('updateBlogForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const updateModal = document.getElementById('updateModal');
    const updateForm = event.target;
    const id = updateModal.dataset.blogId;
    const updatedTitle = updateForm.elements.updateTitle.value;
    const updatedAuthor = updateForm.elements.updateAuthor.value;
    const updatedContent = updateForm.elements.updateContent.value;
    const updatedImgUrl = updateForm.elements.updateImgUrl.value;
    
    const updatedBlog = {
        title: updatedTitle,
        author: updatedAuthor,
        content: updatedContent,
        imgUrl: updatedImgUrl
    };

    axios.patch(`/api/blogs/${id}`, updatedBlog)
        .then(response => {
            console.log('Blog updated successfully:', response.data);
            $(updateModal).modal('hide');
            // Refresh the blog list by fetching all blogs again
            refreshBlogList();
        })
        .catch(error => {
            console.error('Error updating blog:', error);
        });
});

function refreshBlogList() {
    axios.get('/api/blogs')
        .then(response => {
            blogs = response.data;
            const blogList = document.getElementById('blogList');
            blogList.innerHTML = '';

            blogs.forEach(blog => {
                const { id, title, author, content, imgUrl } = blog;

                const listItem = document.createElement('li');

                listItem.innerHTML = `
                    <strong>Title:</strong> ${title}<br>
                    <strong>Author:</strong> ${author}<br>
                    <strong>Content:</strong> ${content}<br>
                    <strong>Image:</strong> <img src="${imgUrl}" alt="Blog Image" style="width: 500px; height: 500px;"><br>
                    <button type="button" class="btn btn-danger" onclick="deleteBlog(${id})">
                        Delete
                    </button>
                    <button type="button" class="btn btn-primary" onclick="openUpdateModal(${id})">
                        Update
                    </button>
                    <hr>
                `;

                blogList.appendChild(listItem);
            });
        })
        .catch(error => {
            console.error(error);
        });
}

</script>
@endsection
