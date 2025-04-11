<template>
    <div  class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true" id="createModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="modalTitleId">{{ modalTitle }}</h5>
                    <button type="button" class="close" @click="$emit('close')" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this record?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancel</button>
                    <button type="button" class="btn btn-danger" @click="$emit('confirm-delete')">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isModalOpen: {
            type: Boolean,
            required: true
        },
        modalTitle: {
            type: String,
            required: true
        },
        modalTitleId: {
            type: String,
            default: "modalTitle"
        }
    }, watch: {
        isModalOpen(newValue) {
            const modalElement = document.getElementById("createModal");
            if (!modalElement) return;
            const modalInstance = bootstrap.Modal.getInstance(modalElement) 
                || new bootstrap.Modal(modalElement);

            if (newValue) {
                modalInstance.show();
            } else {
                modalInstance.hide();
            }
        }
    }
};
</script>

<style scoped>
/* Add any styles specific to the modal here */
</style>
