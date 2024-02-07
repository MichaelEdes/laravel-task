<template>
<div class="flex justify-center items-center min-h-screen w-full bg-gray-100 px-6 ">
    <form class="w-full max-w-lg bg-white shadow-lg rounded-lg p-6" @submit.prevent="submit">
        <h1 class="text-center text-3xl font-bold mb-12">
            Add A Product
        </h1>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label for="name" class="block uppercase tracking-wide text-gray-700 text-s font-bold mb-2">Name (Max 60 Characters):</label>
                <input id="name" v-model="form.name" placeholder="Enter Product Name" type="text" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                <p v-if="errors.name" class="text-red-500 text-xs italic">{{ errors.name[0] }}</p>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label for="price" class="block uppercase tracking-wide text-gray-700 text-s font-bold mb-2">Price:</label>
                <div style="display: flex; flex-direction: row; gap: 0.5rem; align-items: center;">
                    £
                    <input id="price" v-model="form.price" placeholder="Enter Price" type="text" pattern="[0-9]*[.,]?[0-9]+" step="1" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                </div>
                <p v-if="errors.price" class="text-red-500 text-xs italic">{{ errors.price[0] }}</p>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label for="quantity" class="block uppercase tracking-wide text-gray-700 text-s font-bold mb-2">Quantity (Max 100):</label>
                <input id="quantity" v-model="form.quantity" placeholder="Enter Quantity" type="number" required class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                <p v-if="errors.quantity" class="text-red-500 text-xs italic">{{ errors.quantity[0] }}</p>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6">
                <label for="short_description" class="block uppercase tracking-wide text-gray-700 text-s font-bold mb-2">Short Description (Max 150 Characters):</label>
                <textarea id="short_description" v-model="form.short_description" placeholder="Enter Short Description" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"></textarea>
                <p v-if="errors.short_description" class="text-red-500 text-xs italic">{{ errors.short_description[0] }}</p>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 mb-6 relative">
                <label for="categories" class="block uppercase tracking-wide text-gray-700 text-s font-bold mb-2">Categories (Min 1 Category):</label>
                <div class="dropdown-btn bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 w-full relative cursor-pointer flex justify-between items-center" @click="toggleDropdown" tabindex="0" @blur="this.dropdownOpen = false">
                    {{ !form.categories.length > 0 ? "Select Categories" : `${form.categories.length} selected categories` }}
                    <span class="chevron">{{ dropdownOpen ? '▲' : '▼' }}</span>
                    <div v-if="dropdownOpen" class="dropdown-content absolute left-0 mt-1 bg-white border border-gray-200 rounded w-full z-10">
                        <div v-if="categories.length > 0">
                            <div v-for="category in categories" :key="category.id" class="category-item py-2 px-4 hover:bg-gray-100 rounded my-2" :class="{'bg-gray-300': form.categories.includes(category.id)}" @click.stop="toggleCategory(category.id)">
                                <span class="category-name">{{ category.name }}</span>
                                <span v-if="form.categories.includes(category.id)" class="remove-category">x</span>
                            </div>
                        </div>
                        <div v-else>
                          <p>No Categories in database</p>
                        </div>
                    </div>
                </div>
                <p v-if="errors.categories" class="text-red-500 text-xs italic">Please select at least one category.</p>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full px-3 text-right">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Add Product
                </button>
            </div>
        </div>
    </form>
    <div id="successModal" tabindex="-1" aria-hidden="true" :class="{ 'hidden': !modalOpen }" v-animate="'fade'" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden h-modal md:h-full">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex justify-end p-2">
                    <button type="button" class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="successModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-0 text-center">
                    <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900 p-2 flex items-center justify-center mx-auto mb-3.5">
                        <svg aria-hidden="true" class="w-8 h-8 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Success</span>
                    </div>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Product Added Successfully:</h3>
                    <ul class="text-left mb-8">
                        <li class="flex items-center mb-2">
                            <span class="bg-green-500 w-4 h-4 mr-2 rounded-full inline-block"></span>
                            <span class="text-sm text-gray-200">{{ addedProductName }}</span>
                        </li>
                    </ul>
                    <button @click="closeModal" type="button" class="text-white bg-blue-600 hover:bg-blue-800 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import '../../css/app.css';
import axios from 'axios';
export default {
    data() {
        return {
            form: {
                name: '',
                price: '',
                quantity: '',
                short_description: '',
                categories: []
            },
            categories: [],
            dropdownOpen: false,
            errors: {},
            modalOpen: false,
            addedProductName: '',
        };
    },
    mounted() {
        this.fetchCategories();
    },
    methods: {
        closeModal() {
            this.modalOpen = false;
        },
        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
        },
        fetchCategories() {
            axios
                .get('/api/categories')
                .then(response => {
                    this.categories = response.data;
                })
                .catch(error => {
                    console.error('There was an error fetching the categories: ', error);
                });
        },
        submit() {
            this.errors = [];
            axios
                .post('/products/store', this.form)
                .then(() => {
                    this.addedProductName = this.form.name;
                    this.modalOpen = true;
                    this.clearForm();
                    setTimeout(() => {
                        this.modalOpen = false;
                    }, 5000);
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                });
        },
        toggleCategory(id) {
            const index = this.form.categories.indexOf(id);
            if (index === -1) {
                this.form.categories.push(id);
            } else {
                this.form.categories.splice(index, 1);
            }
        },
        clearForm() {
            this.form = {
                name: '',
                price: '',
                quantity: '',
                short_description: '',
                categories: []
            };
        }
    }
};
</script>

<style lang="scss" scoped>
.dropdown-btn {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dropdown-content {
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    top: 100%;
    left: 0;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    max-height: 25rem;
    width: 100%;
    position: absolute;
    z-index: 10;
}

.category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.category-name {
    flex: 1;
}

.remove-category {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 1.1rem;
    height: 1.1rem;
    font-size: 1rem;
    clip-path: circle(50%);
    background-color: rgb(224, 131, 131);
    color: white;
    cursor: pointer;
    padding-bottom: 2px;
    padding-right: 1px;
}

.bg-gray-300 {
    background-color: #e5e5e6;
}
</style>
