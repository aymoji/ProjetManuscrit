<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        #drawingCanvas {
            cursor: crosshair;
            background-color: white;
        }
    </style>
</head>
<body class="font-sans bg-gray-50 text-gray-800">

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8">
        <div class="bg-white shadow-xl rounded-lg max-w-4xl w-full p-8">
            <h1 class="text-4xl font-extrabold text-center mb-6 text-blue-800">Bienvenue sur la page d'accueil</h1>
            <p class="text-center text-gray-600 mb-10">Vous êtes maintenant connecté !</p>

            <div class="space-y-10">
                <div>
                    <p class="text-xl font-semibold mb-4 text-gray-700">Utilisez votre souris pour dessiner ci-dessous :</p>

                    <div class="relative flex justify-center items-center mb-8">
                        
                        <canvas id="drawingCanvas" width="400" height="300" class="border border-gray-300 rounded-md shadow-sm"></canvas>

                        
                        <div class="absolute right-16 flex flex-col space-y-5">
                            
                            <button id="drawButton" class="w-12 h-12 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none shadow-md">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC2klEQVR4nO2aXYhNQRzAf3tXFrvkq7YVD3igVUqe9kGJLR9LPBAlTyi8aFvPsrVln1btm3hAInG1SC4hH9ktIbsriydFEmp9F2vv1dT/1jSdc8/dM5mZrfureblzu+f/O/Of/8yZc6FChTRkgK3AVeAD8BU4D8wmIGqA9UAXcAXoBW4ATdK/CRgEChEtSwBMAzrk7urBfQO2yHc2APkYCdV+eHZgN/BJghkF7gMHgVVAvYzCFPnuvhIy6je8pdFZLRCVQsu0/mrgqPTdLkPGS2rVAnckgC9a+hSZIWJ6oEky61xLTARycvG3QKPRvxh4FZM+cTKvZQSdls5zcvH3wAKjf62MUNyEjpMxR/S/0ynBDANLjb49wEiCRCFCRlWzKpcSLXL3/gArtM9VEO1lChRiZJwxB/gsAbRqn08ATqSQKEhTpdkpWblwj5YGk2SbkVaiy7XERm1eNGjl95aFRM51lVKpMyQX369tRx5YSLwEpuOYvXLxIZGaCTyykBgGFrmWmCXbbBXAZtlm91tIjABrXAU/D5gPLJTyqAK4KKnwxEIiD+xyJaGq0HcjgGsyMn0WEgXgEA5RZfU48A74CJySOXHXUuIYnqmJ2MGOtfW4LrMmqkJdtpS4KTfDG1WW2w7VHgJ1eOaIpcRTHwueyQFLiX4pEF7ZLocHNiPh/WyqGfhtIdEbQjo1yh4orcQ9YKpviQbgjYXEdWCyb4la4LGFxCU5UfFKRlbdtBInZdH0TreFRKfrU484Wi224m0EQlPKtUKV5m0ERDVwZowS6uRwNQGxMoWMKs1LCIjlwF+ZqEWZ0wkSz4C5BETGeFQtRyYXwmptsjMi0MMl0qzb91NdFKrePy+xHugjk9cEg6MlYR7oI1N88xok2QSRUa2aBUsd8KuERJ9Us+BpjhEYAHaEsl9Kot74h8FP4IK8OR0XAkigAyIwKC/u1bPHuCMDvJATDTUyFSpgxz+VBGGyyQngRQAAAABJRU5ErkJggg==" alt="Pen" style="width: 30px; height: 30px;" class="ml-2">
                            </button>                            

                            
                            <button id="eraseButton" class="w-12 h-12 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none shadow-md">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAABeUlEQVR4nO3aMUoEMRTG8b+WO3oGYW0ETyFW2upBlL2HheCCoGexSC5hI2ojiJY2WkQGIoQF2ZnMey+ZZT6YauDBjzcJeWFgypSNyQy4Ap6BR2ABbDGyNMADEFaee2CbkSNGhWnWIEaBaToiqsb0RYQaMbmIUBNmKCLUgJFChJIYaUQogdFCBEuMNiJYYKwQQRNjjQgamB3AxcJvwI8x5lK6E6/APnAGfBtCniQ78QLMk3fnhp350EJYY5aaCCuMi5OmyJpYF60144FdK4QWxpdASGN8SYQUxteAGIrxNSFyMT4XMeuxxeam69bs4paflVtlRFeMG4Jo8xkLHaCf/zBuKIJ4EGuLnWCTVYyTQLS5iAW/gCP0M48jgCiCeCt+k2CO0cte8gVk706lMeoIC4wZQhNjjtDAFENIYoojUswyE1MNYgimOkQOplpEH0z1iC6Y0SD+0t693iWYU+AwjgDiZyfLzohMdqU7s4i/ZLwD13FcnjKFDcgvXiJmAYqbTMIAAAAASUVORK5CYII=" alt="Eraser" style="width: 30px; height: 30px;" class="ml-2">
                            </button>

                            
                            <button id="clearButton" class="w-12 h-12 bg-gray-600 text-white rounded-full hover:bg-gray-700 focus:outline-none shadow-md">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAACeUlEQVR4nO2az2oUQRDGf6dsPCgRNPFmbhHxpCe9KZIgRPQYkmdQQt4irgdFQbz6COqivkEQzya7ycn8YcXFszGSkoYSlnFme6amZ6Y35IMPFra7pr6pmp6q7oFTnFycBx4AbaADbAE/gUOl+72p/7V17BSRoAWsAB+BP4AUpJvzAVhWW7VjElgD9g3OZ3EPWFXbteAesBNQQJLbwEKVAlzon1coIMk3wJnQImaALzWKEOVnYDqUiFkNtzTEnvpQChd1GZWGuQNcsoqYbCidZESamZboVxE4Lwm+sCyxEinn84pw4etG4LBkcDvvS3MtAmfFw8d5orEXgaPi4a7vwV8xGL0JXAf6hrl9nXvLMHdplJBPBoPOEYerBcX0dY7DDcN1O6P6CUspPuxQXjGWOZLgEXAuTcjDEjn7HbimduaAg5xjr3jGioeLaUKeljCY9y6HiIQM8UmakE5Jo77IhIyEKN+mCekFMJx110NHQpSuoP0Pg0DG08RUIUKAH2lCDgNeIJlKIdNJhvirCSFzdQkZnJTU6o7hw75Zx/KbfCaKvDSlzPLbriASljFSgOtVlii+1SlkZBbThEyNWdH4O6toRDejx6WMf88ILBsM3tbGyNpYubl3QjdWrTFqdSfwYDUCR8XDRz4R/6ISwzapZLBXZMdxIQKHJYXHwF0K4mUEjkuCzzCgpRvHEgk38jzgWbgQ0bHCDCUxG7ANtrALXCYQphtKsw09bKrkMPS4JhGvqz57n6841bYsS6wVLd3a3w0o4Ju+sRv5AmJCi7eO7sUWdf5Iq9ilMktraLje4L5uY77Tfnow9FGN+/1V29N1bYrOBvfiFMSBv49GF6BdKh2TAAAAAElFTkSuQmCC" alt="Clear" >
                            </button>
                        </div>
                    </div>

                    <button id="sendDrawing" class="mt-4 px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none shadow-md">
                        Envoyer le dessin
                    </button>
                </div>

                <div>
                    <label for="fileInput" class="text-lg font-semibold block mb-2">Importez un fichier :</label>
                    <input type="file" id="fileInput" name="fileInput" class="border border-gray-300 rounded-md p-2 bg-gray-50 w-full">
                </div>

                <div>
                    <label for="textInput" class="text-lg font-semibold block mb-2">Saisissez un texte :</label>
                    <div class="max-w-2xl mx-auto p-6 bg-blue-100 border-4 border-blue-900 rounded-xl shadow-xl text-center">
                        <div id="predictionDisplay" class="mt-4 text-lg font-semibold text-white py-2 px-4 rounded-md"></div>
                        <div id="filePredictionDisplay" class="mt-4 text-lg font-semibold text-white py-2 px-4 rounded-md"></div>
                    </div>
                </div>
                

                <div class="text-center">
                    <button id="submitButton" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none">
                        Soumettre
                    </button>
                </div>
            </div>
    <script>

        const imageInput = document.getElementById('fileInput');
        const canvas = document.getElementById('drawingCanvas');
        const context = canvas.getContext('2d');
        
        context.fillStyle = 'white';
        context.fillRect(0, 0, canvas.width, canvas.height);
        
        let isDrawing = false;
        let isErasing = false;

        context.strokeStyle = 'grey'; 
        context.lineWidth = 8;        
        context.lineCap = 'round';    
        context.lineJoin = 'round';   

        
        function switchToDrawing() {
            isErasing = false;
            context.strokeStyle = 'grey'; 
            context.lineWidth = 8;        
        }

        function switchToErasing() {
            isErasing = true;
            context.strokeStyle = 'white'; 
            context.lineWidth = 16;       
        }

        canvas.addEventListener('mousedown', (event) => {
            isDrawing = true;
            context.beginPath();
            context.moveTo(event.offsetX, event.offsetY);
        });

        canvas.addEventListener('mousemove', (event) => {
            if (!isDrawing) return;
            context.lineTo(event.offsetX, event.offsetY);
            context.stroke();
        });

        canvas.addEventListener('mouseup', () => isDrawing = false);
        canvas.addEventListener('mouseout', () => isDrawing = false);

        document.getElementById('drawButton').addEventListener('click', switchToDrawing);

        document.getElementById('eraseButton').addEventListener('click', switchToErasing);

        document.getElementById('clearButton').addEventListener('click', () => {
            context.clearRect(0, 0, canvas.width, canvas.height); 
            context.fillStyle = 'white';
            context.fillRect(0, 0, canvas.width, canvas.height); 
        });

        imageInput.addEventListener('change', async (event) => {
            const file = event.target.files[0];
            if (!file) {
                console.error("No file selected!");
                return;
            }

            const formData = new FormData();
            formData.append('image', file);

            const filePredictionDisplay = document.getElementById('filePredictionDisplay');

            try {
                const response = await fetch('http://127.0.0.1:8000/api/predict/', {
                    method: 'POST',
                    body: formData,
                });

                const responseData = await response.json();

                if (!response.ok) {
                    console.error("Server Error:", responseData);
                    filePredictionDisplay.textContent = "Error: " + (responseData.error || "Unknown error");
                    filePredictionDisplay.classList.remove('text-blue-600');
                    filePredictionDisplay.classList.add('text-red-600');
                    return;
                }

                filePredictionDisplay.textContent = `Prediction: ${responseData.prediction}`;
                filePredictionDisplay.classList.remove('text-red-600');
                filePredictionDisplay.classList.add('text-blue-600');
            } catch (error) {
                console.error("Network Error:", error);
                filePredictionDisplay.textContent = "Network Error: " + error.message;
                filePredictionDisplay.classList.remove('text-blue-600');
                filePredictionDisplay.classList.add('text-red-600');
            }
        });

        document.getElementById('sendDrawing').addEventListener('click', async () => {
            const dataUrl = canvas.toDataURL('image/jpeg', 1.0);
            const blob = dataURLtoBlob(dataUrl);
            const formData = new FormData();
            formData.append('image', blob, 'drawing.jpg');

            const predictionDisplay = document.getElementById('predictionDisplay');

            try {
                const response = await fetch('http://127.0.0.1:8000/api/predict/', {
                    method: 'POST',
                    body: formData,
                });

                const responseData = await response.json();

                if (!response.ok) {
                    console.error("Server Error:", responseData);
                    predictionDisplay.textContent = "Error: " + (responseData.error || "Unknown error");
                    predictionDisplay.classList.remove('text-blue-600');
                    predictionDisplay.classList.add('text-red-600');
                    return;
                }

                predictionDisplay.textContent = `Prediction: ${responseData.prediction}`;
                predictionDisplay.classList.remove('text-red-600');
                predictionDisplay.classList.add('text-blue-600');
            } catch (error) {
                console.error("Network Error:", error);
                predictionDisplay.textContent = "Network Error: " + error.message;
                predictionDisplay.classList.remove('text-blue-600');
                predictionDisplay.classList.add('text-red-600');
            }
        });

        function dataURLtoBlob(dataUrl) {
            const arr = dataUrl.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            const len = bstr.length;
            const u8arr = new Uint8Array(len);

            for (let i = 0; i < len; i++) {
                u8arr[i] = bstr.charCodeAt(i);
            }

            return new Blob([u8arr], { type: mime });
        }
    </script>

</body>
</html>
