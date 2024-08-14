<template>
    <Head title="Dashboard" />
    <div class="p-6 bg-white rounded-lg shadow-md">
        <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Year Selector -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="year">
                    Select Year
                </label>
                <select v-model="selectedYear" id="year" @change="handleParamChange" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option v-for="year in years" :key="year" :value="year">
                        {{ year }}
                    </option>
                </select>
            </div>

            <!-- Month Selector -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="month">
                    Select Month
                </label>
                <select v-model="selectedMonth" id="month" @change="handleParamChange" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option v-for="month in months" :key="month.value" :value="month.value">
                        {{ month.label }}
                    </option>
                </select>
            </div>

            <!-- Week Selector -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="week">
                    Select Week
                </label>
                <select v-model="selectedWeek" id="week" @change="handleParamChange" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option v-for="week in weeks" :key="week" :value="week">
                        Week {{ week }}
                    </option>
                </select>
            </div>

            <!-- Availability Checkboxes -->
            <div class="grid grid-cols-7 gap-4">
                <div class="flex items-center" v-for="(day, key) in availability" :key="key">
                    <span class="font-medium text-gray-900 capitalize">{{ key }}</span>
                    <input type="checkbox" v-model="availability[key]" class="ml-2">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="isDisabled" class="mt-6 px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 disabled:opacity-50">
                Save
            </button>&nbsp;
            <a href="/dashboard" class="mt-6 px-4 py-2 bg-green-500 text-white rounded-md shadow hover:bg-green-600">
                Go Back
            </a>&nbsp;
            <inertia-link :disabled="!isDisabled" :href="route('availablity', { id: studentId })"
            :class="[
                'bg-red-500 text-white px-4 py-2 rounded shadow hover:bg-red-700',
                { 'opacity-50 cursor-not-allowed pointer-events-none': !isDisabled }
                ]"
                :aria-disabled="!isDisabled"
                >
                Reset
            </inertia-link>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';
import { InertiaLink } from '@inertiajs/inertia-vue3';
import { Head } from '@inertiajs/vue3';

// Define months and weeks
const months = [
    { value: '1', label: 'January' },
    { value: '2', label: 'February' },
    { value: '3', label: 'March' },
    { value: '4', label: 'April' },
    { value: '5', label: 'May' },
    { value: '6', label: 'June' },
    { value: '7', label: 'July' },
    { value: '8', label: 'August' },
    { value: '9', label: 'September' },
    { value: '10', label: 'October' },
    { value: '11', label: 'November' },
    { value: '12', label: 'December' }
];
const weeks = [1, 2, 3, 4];

// Generate year options for the last 5 years including the current year
const currentYear = new Date().getFullYear();
const years = Array.from({ length: 6 }, (_, i) => currentYear - i);

// Access data from the server
const props = defineProps({
  data: Object,
  sId:String,
  year: String,
  month:String,
  week: String,
  isDisabled:Boolean
});

const studentId = props.data !== null ? props.data?.student_id : props.sId;
// const studentId = ref(stdId);
const selectedYear = ref(props.year || currentYear);
const selectedMonth = ref(props.month || new Date().getMonth() + 1); // Months are 0-indexed
const selectedWeek = ref(props.week || Math.ceil(new Date().getDate() / 7));
const isDisabled = props.isDisabled;

const availability = ref({
    monday: props.data?.monday === 1,
    tuesday: props.data?.tuesday === 1,
    wednesday: props.data?.wednesday === 1,
    thursday: props.data?.thursday === 1,
    friday: props.data?.friday === 1,
    saturday: props.data?.saturday === 1,
    sunday: props.data?.sunday === 1,
});

const isFetching = ref(false);

const handleParamChange = () => {
    isFetching.value = true;
    fetchAvailability();
};

const fetchAvailability = () => {
    Inertia.get(`/add-availablity/${studentId}`, {
        year: selectedYear.value,
        month: selectedMonth.value,
        week: selectedWeek.value,
        isDisabled:true
    }, {
        preserveState: true,
        onFinish: (page) => {
            const data = page.props.data;
            // Update availability based on the fetched data
            availability.value = {
                monday: data?.monday === 1,
                tuesday: data?.tuesday === 1,
                wednesday: data?.wednesday === 1,
                thursday: data?.thursday === 1,
                friday: data?.friday === 1,
                saturday: data?.saturday === 1,
                sunday: data?.sunday === 1,
            };
            // Preserve dropdown state
            selectedYear.value = data?.year || selectedYear.value;
            selectedMonth.value = data?.month || selectedMonth.value;
            selectedWeek.value = data?.week || selectedWeek.value;
            // isFetching.value = true;
        }
    });
};

const submitForm = async () => {
    try {
        const response = await axios.post(`/students/${studentId}/availability`, {
            year: selectedYear.value,
            month: selectedMonth.value,
            week: selectedWeek.value,
            student_id: studentId,
            ...availability.value
        });
        alert(response.data.message);
        console.log('Success:', response.data.message);
    } catch (error) {
        console.error('An error occurred:', error.response.data);
    }
};
</script>
