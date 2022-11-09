<template>
    <div id="content">
        <div>
            <button type="button" class="btn btn-primary" style="float: right;" data-bs-toggle="modal"
                @click="addbook()" data-bs-target="#exampleModal">
                Add Book
            </button>
            <div class="autoscroll mt-3">
                <!-- <h3 class="text-center" v-if="lists.length < 1">List Empty</h3> -->
                <h3 class="text-center" >All Books</h3>
                <h5 v-if="lists.length < 1">Please click on "Add Book" button to add books</h5>
                <!-- <ul class="list-group">
                <li
                    class="list-group-item"
                    v-for="books in lists"
                    :key="books.id"
                >
                    {{ books.name }} - {{ books.author }} - {{ books.image }}
                    <span class="float-right" style="float: right ;">
                        <button
                            class="btn btn-warning btn-sm mr-2"
                            @click="editbook(books)"
                        >Edit</button>
                        <button
                            class="btn btn-danger btn-sm mr-2"
                            @click="deletebook(books.id)"
                        >Delete</button>
                    </span>
                </li>
            </ul> -->
                <table class="table table-hover" style="width:100% ;" v-if="lists.length > 0">
                    <thead>
                        <tr>
                            <th scope="col">Book name</th>
                            <th scope="col">Author name</th>
                            <!-- <th scope="col">Date</th> -->
                            <th scope="col">Preview</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="pt-3" v-for="books in lists" :key="books.id">
                            <td scope="row">{{ books.name }}</td>
                            <td>{{ books.author }}</td>
                            <!-- <td>{{ books.created_at }}</td> -->
                            <td>
                                <img :src="'/images/' + books.image" width='180'>
                            </td>
                            <td class="col-2"><button type="button" class="btn btn-warning btn-sm mr-2"
                                    data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    @click="editbook(books)">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm mr-2"
                                    @click="deletebook(books.id)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="border p-3">
                            <form @submit.prevent="profileUpload" method="post" enctype="multipart/form-data">
                                <div class="mb-2">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" placeholder="Enter Name" class="form-control" id="name"
                                        v-model="item.name">
                                </div>
                                <div class="mb-2">
                                    <label for="author" class="form-label">Author</label>
                                    <input type="text" placeholder="Enter author" class="form-control" id="author"
                                        v-model="item.author">
                                </div>
                                <div class="mb-2">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image" v-on:change="onChange">
                                </div>
                                <div v-if="item.image" class="mt-3 mb-3">
                                    <img :src="'/images/' + item.image" width='180'>
                                </div>
                                <div class="d-grid">
                                    <button class="btn btn-success" type="submit" data-bs-dismiss="modal">
                                        {{ isEditing ? "Update" : "Save" }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "Library",
    data() {
        return {
            lists: [],
            item: {
                name: "",
                author: "",
                image: "",
                imagePreview: null,
                created_at: "",
            },

            imagePath: null,
            isEditing: false,
            temp_id: null
        }
    },
    mounted() {
        this.fetchAll()
    },
    methods: {
        fetchAll() {
            try {
                axios.get('/api/books')
                    .then(res => this.lists = res.data)
            } catch (e) {
                console.log(e)
            }
        },
        onChange(e) {
            console.log("File", e.target.files[0]);
            this.item.image = e.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(this.item.image);
            reader.onload = e => {
                this.item.imagePreview = e.target.result;
            };
        },

        profileUpload() {

            let data = new FormData;
            data.append('name', this.item.name);
            data.append('author', this.item.author);
            let reader = new FileReader();
            reader.readAsDataURL(this.item.image);
            reader.onload = e => {
                this.item.image = reader.result;
            };
            data.append('image', this.item.image);

            let method = axios.post
            let url = "/api/books"
            if (this.temp_id) {
                method = axios.post
                url = '/api/books/' + this.temp_id
            }

            try {
                method(url, data)
                    .then(res => {
                        this.fetchAll()
                        console.log(this.temp_id);
                        this.temp_id = null
                        this.isEditing = false
                    })
            }
            catch (e) {
                console.log(e)
            }
        },

        addbook() {
            this.item = {
                name: "",
                author: "",
                image: "",
            }
            this.temp_id = null
            this.isEditing = false
        },
        editbook(book) {
            this.item = {
                name: book.name,
                author: book.author,
                // image: book.image,
            }
            this.temp_id = book.id;
            this.isEditing = true
        },


        deletebook(id) {
            try {
                axios.delete(`/api/books/${id}`)
                    .then(res => {
                        this.fetchAll()
                    })
            } catch (e) {
                console.log(e)
            }
        }
    }
}
</script>

<style scoped>

</style>
