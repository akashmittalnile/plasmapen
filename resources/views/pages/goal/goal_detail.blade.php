<div class="modal fade" id="viewGoalModal" tabindex="-1" aria-labelledby="viewGoalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewGoalModalLabel">Goal Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <p>{{ optional($goal->user)->name ?? 'No user assigned' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <p>{{ optional($goal->user)->email ?? 'No email' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <p>{{ $goal->goal_type }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Achieve Date</label>
                        <p>{{ \Carbon\Carbon::parse($goal->achieve_date)->format('d M Y') }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Goal Statement</label>
                        <p>{{ $goal->goal_statement }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label">What will it take for me to get this done?</label>
                        <p>{{ $goal->goal_for_me }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Six Month Milestones</label>
                        <p>{{ $goal->six_month_milestones }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Optional: You can initialize modal options if needed
        var myModal = new bootstrap.Modal(document.getElementById('viewGoalModal'), {
            backdrop: 'static', // Prevents closing the modal when clicking outside
            keyboard: false // Prevents closing the modal with the escape key
        });

        // Event handler for showing modal when clicking View icon
        $('.Viewiconbtn').on('click', function(event) {
            event.preventDefault();
            var goalId = $(this).data('goal-id');

            // Example AJAX call to fetch goal details
            $.ajax({
                url: '/goals/' + goalId, // Adjust URL as per your route setup
                type: 'GET',
                success: function(response) {
                    $('#viewGoalModal .modal-body').html(response);
                    myModal.show(); // Show the modal after content is loaded
                },
                error: function(error) {
                    console.error('Error loading goal details:', error);
                }
            });
        });
    });
</script>
