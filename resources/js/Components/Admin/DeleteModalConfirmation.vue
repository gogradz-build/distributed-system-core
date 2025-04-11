<template>
    <div v-if="true" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Warning !</h5>
            <button type="button" class="close" @click="closeModal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Deleting this record is a permanent action and cannot be undone. Please confirm if you want to proceed.
            <h3>Are you sure you want to delete?</h3>
            <p>Student ID: {{ studentId }}</p>
            <p>Route: {{ routeName }}</p>
            <button @click="confirmDeletion">Confirm</button>
            <button @click="closeModal">Cancel</button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
            <button type="button" class="btn btn-danger" @click="confirmDeletion">Confirm</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
 
  export default {
    name: 'DeleteModalConfirmation',
    props: {
      studentId: {
        type: [String, Number],
        required: true,
      },
      routeName: {
        type: String,
        required: true,
      },
    },
    setup() {
      // Reactive state for modal visibility
      const showModal = ref(true);
  
      // Method to close the modal
      const closeModal = () => {
        showModal.value = false;
      };
  
      // Method to handle deletion logic
      const confirmDeletion = () => {
        // Perform the deletion logic (e.g., an API call)
        console.log(`Deleting student with ID: ${studentId}`);
        closeModal(); // Close modal after deletion confirmation
      };
  
      // Expose variables and methods to the template
      return {
        showModal,
        closeModal,
        confirmDeletion,
      };
    },
   
  };
 
  </script>
  