<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white shadow-2xl rounded-xl p-8 w-full max-w-2xl">
        <h1 class="text-4xl font-bold text-center text-blue-600 mb-8 uppercase tracking-wide">Task Management</h1>
        
        <div class="bg-blue-50 rounded-lg p-6 mb-6">
            <form id="add-task-form" class="flex space-x-4">
                <input 
                    type="text" 
                    name="name" 
                    placeholder="Enter a new task" 
                    required 
                    class="flex-grow px-4 py-2 border-2 border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                >
                <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center space-x-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Add Task</span>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto rounded-lg shadow-md">
            <table class="w-full bg-white">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="task-table" class="divide-y divide-gray-200">
                    <!-- Rows will be dynamically filled -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const apiBaseUrl = '/api/v1';

        // Fetch tasks and display them in the table
        const fetchTasks = () => {
            axios.get(`${apiBaseUrl}/tasks`)
                .then(response => {
                    const taskTable = document.getElementById('task-table');
                    taskTable.innerHTML = '';
                    response.data.data.forEach(task => {
                        taskTable.innerHTML += `
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-4 py-3 text-gray-700">${task.id}</td>
                                <td class="px-4 py-3 font-medium ${task.is_completed ? 'line-through text-gray-400' : 'text-gray-800'}">${task.name}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold ${task.is_completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                                        ${task.is_completed ? 'Completed' : 'Pending'}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button onclick="deleteTask(${task.id})" class="text-red-500 hover:text-red-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button onclick="markComplete(${task.id})" class="text-green-500 hover:text-green-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => console.error(error));
        };

        // Add a new task
        document.getElementById('add-task-form').addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(event.target);
            const taskName = formData.get('name');

            axios.post(`${apiBaseUrl}/tasks`, { name: taskName, is_completed: false })
                .then(() => {
                    event.target.reset();
                    fetchTasks();
                })
                .catch(error => console.error(error));
        });

        // Delete a task
        const deleteTask = (taskId) => {
            axios.delete(`${apiBaseUrl}/tasks/${taskId}`)
                .then(() => fetchTasks())
                .catch(error => console.error(error));
        };

        // Mark a task as complete
        const markComplete = (taskId) => {
            axios.patch(`${apiBaseUrl}/tasks/${taskId}/complete`)
                .then(() => fetchTasks())
                .catch(error => console.error(error));
        };

        // Fetch tasks on page load
        fetchTasks();
    </script>
</body>
</html>