<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Feedback') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-md mx-auto">
                        <h2 class="text-2xl font-bold mb-6 text-gray-800">Share Your Feedback</h2>

                        <form id="feedbackForm" class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Your Name</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="John Doe">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="you@example.com">
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700">Your Feedback</label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="4"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Your thoughts..."></textarea>
                            </div>

                            <div>
                                <button
                                    type="submit"
                                    id="submitButton"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span id="buttonText">Submit Feedback</span>
                                    <span id="spinner" class="hidden">...</span>
                                </button>
                            </div>

                            <div id="responseMessage" class="hidden p-4 rounded-lg"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('feedbackForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const spinner = document.getElementById('spinner');
            const responseMessage = document.getElementById('responseMessage');

            submitButton.disabled = true;
            buttonText.textContent = 'Processing....';
            spinner.classList.remove('hidden');
            responseMessage.classList.add('hidden');

            const payload = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                message: document.getElementById('message').value
            };

            try {
                const response = await fetch('/feedback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content                    
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                responseMessage.classList.remove('hidden');
                if (response.ok && data.success) {
                    responseMessage.className = 'p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg';
                    responseMessage.innerHTML = `<strong>Success!</strong> ${data.message} <div class="mt-2">We've sent a confirmation to ${payload.email}.</div>`;
                    this.reset();
                } else {
                    throw new Error(data.message || 'Failed to submit feedback');
                }
            } catch (error) {
                responseMessage.className = 'p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg';
                responseMessage.textContent = `Error: ${error.message}`;
                console.error('Submission error:', error);
            } finally {
                submitButton.disabled = false;
                buttonText.textContent = 'Submit Feedback: ';
                spinner.classList.add('hidden');

                if (responseMessage.classList.contains('hidden') === false) {
                    setTimeout(() => {
                        responseMessage.classList.add('hidden');
                    }, 5000);
                }
            }
        })
    </script>


</x-app-layout>
