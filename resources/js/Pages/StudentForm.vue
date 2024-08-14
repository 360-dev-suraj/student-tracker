<template>
    <Head title="Dashboard" />
    <div class="form-container">
      <h1 class="form-title">Student Registration</h1>
      <form @submit.prevent="submitForm" novalidate>
        <div class="form-group">
          <label for="first_name">First Name</label>
          <input
            type="text"
            id="first_name"
            v-model="form.firstName"
            :class="{ 'is-invalid': errors.firstName }"
            placeholder="Enter your first name"
            required
          />
          <span v-if="errors.firstName" class="error">{{ errors.firstName }}</span>
        </div>

        <div class="form-group">
          <label for="middle_name">Middle Name</label>
          <input
            type="text"
            id="middle_name"
            v-model="form.middleName"
            placeholder="Enter your middle name"
          />
        </div>

        <div class="form-group">
          <label for="last_name">Last Name</label>
          <input
            type="text"
            id="last_name"
            v-model="form.lastName"
            :class="{ 'is-invalid': errors.lastName }"
            placeholder="Enter your last name"
            required
          />
          <span v-if="errors.lastName" class="error">{{ errors.lastName }}</span>
        </div>

        <div class="form-group">
          <label for="dob">Date of Birth</label>
          <input
            type="date"
            id="dob"
            v-model="form.dob"
            :class="{ 'is-invalid': errors.dob }"
            :max="maxDate"
            required
          />
          <span v-if="errors.dob" class="error">{{ errors.dob }}</span>
        </div>

        <button type="submit" class="submit-button">Submit</button> &nbsp;
        <a href="/dashboard"
                        class="back-button">
                        Go Back
    </a>
      </form>
    </div>
  </template>

  <script>
  import { useNotification } from '@kyvg/vue3-notification';
  import { Head } from '@inertiajs/vue3';
  export default {
    data() {
      return {
        form: {
          firstName: '',
          middleName: '',
          lastName: '',
          dob: ''
        },
        errors: {}
      };
    },
    computed: {
        maxDate() {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
        const dd = String(today.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
        },
    },

    methods: {
        async submitForm() {
        this.errors = this.validateForm();
        if (Object.keys(this.errors).length === 0) {
            try {
            const response = await axios.post('/students', this.form);
                alert(response.data.message);
                this.$inertia.visit('/dashboard');
            } catch (error) {
                console.error('An error occurred:', error);
            }
        }
      },
      validateForm() {
        const errors = {};
        if (!this.form.firstName) errors.firstName = 'First name is required';
        if (!this.form.lastName) errors.lastName = 'Last name is required';
        if (!this.form.dob) errors.dob = 'Date of birth is required';

        return errors;
      }
    }
  };
  </script>

  <style scoped>
  .form-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 2em;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .form-title {
    font-size: 1.5em;
    margin-bottom: 1em;
    color: #333;
  }

  .form-group {
    margin-bottom: 1.5em;
  }

  label {
    display: block;
    margin-bottom: 0.5em;
    font-weight: bold;
    color: #555;
  }

  input {
    width: 100%;
    padding: 0.75em;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
  }

  input.is-invalid {
    border-color: #e74c3c;
  }

  .error {
    color: #e74c3c;
    font-size: 0.875em;
    margin-top: 0.25em;
  }

  .submit-button {
    display: inline-block;
    padding: 0.75em 1.5em;
    font-size: 1em;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }

  .back-button {
    display: inline-block;
    padding: 0.75em 1.5em;
    font-size: 1em;
    color: #fff;
    background-color: #1e7215;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }

  .submit-button:hover {
    background-color: #0056b3;
  }
  </style>
