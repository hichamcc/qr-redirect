
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Links') }}
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-4">
                        <form action="{{ route('links.index') }}" method="GET" class="flex flex-col gap-2 items-start">
                            <textarea
                                name="search"
                                id="search"
                                placeholder="Enter codes to search (one per line)"
                                class="w-full h-24 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            ></textarea>
                            <div class="flex gap-2">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Search
                                </button>
                                <button type="button" onclick="clearSearch()" class="text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 focus:outline-none">
                                    Clear
                                </button>
                            </div>
                        </form>
                        <div>
                            <button type="button" data-modal-target="generateLinksModal" data-modal-toggle="generateLinksModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Generate Links
                            </button>
                        </div>

                    </div>
                    <form id="bulk-update-form" method="POST" action="{{ route('links.bulk-update') }}">
                        @csrf
                        <div id="bulk-update-controls" class="mt-4 hidden">
                            <input type="text" name="bulk_redirect_url" placeholder="Enter new URL for selected items" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mr-2 w-1/2">
                            <button type="button" onclick="bulkUpdate()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Update Selected
                            </button>
                        </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Redirect URL</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden">Test QR Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($links as $link)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="selected[]" value="{{ $link->id }}" class="form-checkbox h-5 w-5 text-blue-600 row-checkbox">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $link->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $link->redirect_url ?? 'Not set' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $link->created_at->format('Y-m-d H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden">
                                        {!! QrCode::size(100)->generate(route('qr.redirect', ['code' => $link->code])) !!}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap ">
                                        <button type="button" onclick="openEditModal('{{ $link->id }}', '{{ $link->redirect_url }}')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1 mr-2  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                            Edit URL
                                        </button>

                                        <button type="button" onclick="showQRCode('{{ $link->code }}')" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-1 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                                            Show QR
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </form>

                    <div class="mt-4">
                        {{ $links->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- QR Code Modal -->
    <div id="qrModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                QR Code
                            </h3>
                            <div class="mt-2" id="qrCodeContainer">
                                <!-- QR code will be inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeQRModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Links Modal -->
    <div id="generateLinksModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="generateLinksModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="px-6 py-6 lg:px-8">
                    <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Generate Links</h3>
                    <form id="generate-form" action="{{ route('links.generate') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="count" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Number of links to generate</label>
                            <input type="number" name="count" id="count" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        </div>
                        <button type="submit" id="generate-button" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Generate Links
                        </button>
                        <div id="loading-spinner" class="hidden text-center">
                            <div role="status">
                                <svg aria-hidden="true" class="inline w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                </svg>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

  <!-- Edit URL Modal -->
  <div id="editUrlModal" tabindex="-1" aria-hidden="true" class="bg-opacity-50 fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" id="close-edit" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="editUrlModal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="px-6 py-6 lg:px-8">
                <h3 class="mb-4 text-xl font-medium text-gray-900 dark:text-white">Edit Redirect URL</h3>
                <form id="edit-url-form" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="edit-link-id" name="link_id">
                    <div>
                        <label for="redirect_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Redirect URL</label>
                        <input type="url" name="redirect_url" id="redirect_url" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update URL</button>
                    <div id="loading-spinner" class="hidden text-center">
                        <div role="status">
                            <svg aria-hidden="true" class="inline w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="alert-container" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4"></div>


@push('scripts')
<script>

    // add value get search to #search
    $(document).ready(function() {
        var $bulkUpdateControls = $('#bulk-update-controls');

        function updateBulkControls() {
            var checkedBoxes = $('.row-checkbox:checked').length;
            $bulkUpdateControls.toggleClass('hidden', checkedBoxes === 0);
        }

        $('.row-checkbox').on('change', updateBulkControls);

        // Initialize the bulk update controls visibility
        updateBulkControls();

        // Populate search input if there's a previous search query
        $('#search').val({!! json_encode(request()->search) !!});
    });



    function showQRCode(code) {
        const qrCodeContainer = document.getElementById('qrCodeContainer');
        qrCodeContainer.innerHTML = '<div class="text-center">Loading QR Code...</div>';
        document.getElementById('qrModal').classList.remove('hidden');

        fetch(`/generate-qr/${code}`)
            .then(response => response.text())
            .then(data => {
                qrCodeContainer.innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
                qrCodeContainer.innerHTML = '<div class="text-red-500">Error loading QR Code</div>';
            });
    }

    function closeQRModal() {
        document.getElementById('qrModal').classList.add('hidden');
    }

    function bulkUpdate() {
        var form = document.getElementById('bulk-update-form');
        var selected = form.querySelectorAll('input[name="selected[]"]:checked');

        if (selected.length === 0) {
            alert('Please select at least one item to update.');
            return;
        }

        var newUrl = form.elements['bulk_redirect_url'].value.trim();
        if (!newUrl) {
            alert('Please enter a new URL for the selected items.');
            return;
        }

        form.submit();

    }


    function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            const alertHTML = `
                <div id="alert-${type}" class="flex p-4 mb-4 text-${type}-800 rounded-lg bg-${type}-50 dark:bg-gray-800 dark:text-${type}-400" role="alert">
                    <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">${type} alert</span>
                    <div class="ml-3 text-sm font-medium">
                        ${message}
                    </div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-${type}-50 text-${type}-500 rounded-lg focus:ring-2 focus:ring-${type}-400 p-1.5 hover:bg-${type}-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-${type}-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-${type}" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            `;
            alertContainer.innerHTML = alertHTML;

            // Auto-dismiss the alert after 5 seconds
            setTimeout(() => {
                const alertElement = document.getElementById(`alert-${type}`);
                if (alertElement) {
                    alertElement.remove();
                }
            }, 3000);
        }

        function showSuccessAlert(message) {
            showAlert(message, 'green');
        }

        function showErrorAlert(message) {
            showAlert(message, 'red');
        }

        $(document).on('click' , '#close-edit' , function(){

            // perform click esc by js code
            document.querySelector('#editUrlModal').click();

        })

    // Generate Links Form Submission
    document.getElementById('generate-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const generateButton = document.getElementById('generate-button');
            const loadingSpinner = document.getElementById('loading-spinner');

            generateButton.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');

            fetch('/links/generate', {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                showSuccessAlert(data.message);
                window.location.href = data.download_url;
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorAlert('An error occurred while generating links.');
            })
            .finally(() => {
                generateButton.classList.remove('hidden');
                loadingSpinner.classList.add('hidden');
            });
        });

        // Edit URL Modal and Form Submission
        function openEditModal(linkId, currentUrl) {
            document.getElementById('edit-link-id').value = linkId;
            document.getElementById('redirect_url').value = currentUrl || '';
            const editUrlModal = new Modal(document.getElementById('editUrlModal'));
            editUrlModal.show();
        }

        document.getElementById('edit-url-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const linkId = document.getElementById('edit-link-id').value;
            const redirectUrl = document.getElementById('redirect_url').value;
            fetch(`/links/${linkId}/update-url`, {
                method: 'PATCH',
                body: JSON.stringify({
                    redirect_url: redirectUrl
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    // Handle validation errors
                    let errorMessage = 'Validation failed:';
                    for (let field in data.errors) {
                        errorMessage += `\n${field}: ${data.errors[field].join(', ')}`;
                    }
                    showErrorAlert(errorMessage);
                } else {
                    // Handle success
                    showSuccessAlert(data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorAlert('An error occurred while updating the URL.');
            });
        });

    function clearSearch() {
        document.getElementById('search').value = '';
    }

</script>
@endpush
</x-app-layout>
