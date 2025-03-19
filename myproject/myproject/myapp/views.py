from django.shortcuts import render
from rest_framework.viewsets import ModelViewSet
import os
from django.conf import settings
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework.parsers import MultiPartParser, FormParser
from .models import Image
from .serializers import ImageSerializer


class ImageViewSet(ModelViewSet):
    queryset = Image.objects.all()
    serializer_class = ImageSerializer


from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework.parsers import MultiPartParser, FormParser
from .models import Image
from .serializers import ImageSerializer
import os
from django.conf import settings
import cv2
import numpy as np
import matplotlib.pyplot as plt
import numpy as np
import tensorflow as tf
import matplotlib.pyplot as plt
from tensorflow.keras.backend import ctc_decode
from keras import ops









import tensorflow as tf
from tensorflow.keras.models import load_model
from tensorflow.keras import layers
from keras.utils import custom_object_scope
import keras
from keras.layers import StringLookup
import keras
from keras import ops
import matplotlib.pyplot as plt
import tensorflow as tf
import numpy as np
import os

batch_size = 64
padding_token = 99
image_width = 128
image_height = 32

import pickle


with open('myapp/characters.pkl', 'rb') as file:
    characters = pickle.load(file)


AUTOTUNE = tf.data.AUTOTUNE


char_to_num = StringLookup(vocabulary=list(characters), mask_token=None)


num_to_char = StringLookup(vocabulary=char_to_num.get_vocabulary(), mask_token=None, invert=True)


class CTCLayer(keras.layers.Layer):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.loss_fn = tf.keras.backend.ctc_batch_cost

    def call(self, y_true, y_pred):
        batch_len = tf.cast(tf.shape(y_true)[0], dtype="int64")
        input_length = tf.cast(tf.shape(y_pred)[1], dtype="int64")
        label_length = tf.cast(tf.shape(y_true)[1], dtype="int64")

        input_length = input_length * tf.ones(shape=(batch_len, 1), dtype="int64")
        label_length = label_length * tf.ones(shape=(batch_len, 1), dtype="int64")
        loss = self.loss_fn(y_true, y_pred, input_length, label_length)
        self.add_loss(loss)

        return y_pred


def build_model():
 
    input_img = keras.Input(shape=(image_width, image_height, 1), name="image")
    labels = keras.layers.Input(name="label", shape=(None,))

    x = keras.layers.Conv2D(
        32,
        (3, 3),
        activation="relu",
        kernel_initializer="he_normal",
        padding="same",
        name="Conv1",
    )(input_img)
    x = keras.layers.MaxPooling2D((2, 2), name="pool1")(x)

    x = keras.layers.Conv2D(
        64,
        (3, 3),
        activation="relu",
        kernel_initializer="he_normal",
        padding="same",
        name="Conv2",
    )(x)
    x = keras.layers.MaxPooling2D((2, 2), name="pool2")(x)

   
    new_shape = ((image_width // 4), (image_height // 4) * 64)
    x = keras.layers.Reshape(target_shape=new_shape, name="reshape")(x)
    x = keras.layers.Dense(64, activation="relu", name="dense1")(x)
    x = keras.layers.Dropout(0.2)(x)

    
    x = keras.layers.Bidirectional(
        keras.layers.LSTM(128, return_sequences=True, dropout=0.25)
    )(x)
    x = keras.layers.Bidirectional(
        keras.layers.LSTM(64, return_sequences=True, dropout=0.25)
    )(x)

   
    x = keras.layers.Dense(
        len(char_to_num.get_vocabulary()) + 2, activation="softmax", name="dense2"
    )(x)

   
    output = CTCLayer(name="ctc_loss")(labels, x)

    # Define the model.
    model = keras.models.Model(
        inputs=[input_img, labels], outputs=output, name="handwriting_recognizer"
    )
    # Optimizer.
    opt = keras.optimizers.Adam()
    # Compile the model and return.
    model.compile(optimizer=opt)
    return model


# Get the model.
model = build_model()
model.summary()


def detect_and_crop_word(image_path, padding=10):
    # Read the image
    image = cv2.imread(image_path)
    
    # Convert to grayscale
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    
    # Threshold to create a binary image (invert the colors so text is white)
    _, thresh = cv2.threshold(gray, 127, 255, cv2.THRESH_BINARY_INV)
    
    # Find contours (the edges of the white areas after thresholding)
    contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    
    # Sort contours by area, descending (largest area first)
    contours = sorted(contours, key=cv2.contourArea, reverse=True)
    
    # Initialize variables to track the bounding box of the word
    x_min, y_min, x_max, y_max = float('inf'), float('inf'), -float('inf'), -float('inf')
    
    # Loop over all contours and find the bounding box that encloses the whole word
    for contour in contours:
        x, y, w, h = cv2.boundingRect(contour)
        
        # Extend the bounding box to encompass the entire word (if needed)
        x_min = min(x_min, x)
        y_min = min(y_min, y)
        x_max = max(x_max, x + w)
        y_max = max(y_max, y + h)
    
    # Add padding to the bounding box (optional)
    x_min -= padding
    y_min -= padding
    x_max += padding
    y_max += padding
    
    # Make sure bounding box is within image dimensions
    x_min = max(x_min, 0)
    y_min = max(y_min, 0)
    x_max = min(x_max, image.shape[1])
    y_max = min(y_max, image.shape[0])
    
    # Crop the image to remove extra whitespace
    cropped_image = image[y_min:y_max, x_min:x_max]
    
    # Draw the bounding box on the original image for visualization (optional)
    cv2.rectangle(image, (x_min, y_min), (x_max, y_max), (255, 255, 255), 2)
    
    return (x_min, y_min, x_max, y_max), cropped_image





def distortion_free_resize(image, img_size):
    w, h = img_size
    image = tf.image.resize(image, size=(h, w), preserve_aspect_ratio=True)

    # Check tha amount of padding needed to be done.
    pad_height = h - ops.shape(image)[0]
    pad_width = w - ops.shape(image)[1]

    # Only necessary if you want to do same amount of padding on both sides.
    if pad_height % 2 != 0:
        height = pad_height // 2
        pad_height_top = height + 1
        pad_height_bottom = height
    else:
        pad_height_top = pad_height_bottom = pad_height // 2

    if pad_width % 2 != 0:
        width = pad_width // 2
        pad_width_left = width + 1
        pad_width_right = width
    else:
        pad_width_left = pad_width_right = pad_width // 2

    image = tf.pad(
        image,
        paddings=[
            [pad_height_top, pad_height_bottom],
            [pad_width_left, pad_width_right],
            [0, 0],
        ],
    )
    image = ops.transpose(image, (1, 0, 2))
    image = tf.image.flip_left_right(image)
    return image


def preprocess_image(image_path, img_size=(image_width, image_height)):
    image = tf.io.read_file(image_path)
    image = tf.image.decode_png(image, 1)
    image = distortion_free_resize(image, img_size)
    image = ops.cast(image, tf.float32) / 255.0
    return image


def vectorize_label(label):
    label = char_to_num(tf.strings.unicode_split(label, input_encoding="UTF-8"))
    length = ops.shape(label)[0]
    pad_amount = max_len - length
    label = tf.pad(label, paddings=[[0, pad_amount]], constant_values=padding_token)
    return label


def process_images_labels(image_path, label):
    image = preprocess_image(image_path)
    label = vectorize_label(label)
    return {"image": image, "label": label}


def prepare_dataset(image_paths, labels):
    dataset = tf.data.Dataset.from_tensor_slices((image_paths, labels)).map(
        process_images_labels, num_parallel_calls=AUTOTUNE
    )
    return dataset.batch(batch_size).cache().prefetch(AUTOTUNE)

def decode_batch_predictions(pred):
    input_len = np.ones(pred.shape[0]) * pred.shape[1]
    # Use greedy search. For complex tasks, you can use beam search.
    results = keras.ops.nn.ctc_decode(pred, sequence_lengths=input_len)[0][0][
        :, :78
    ]
    # Iterate over the results and get back the text.
    output_text = []
    for res in results:
        res = tf.gather(res, tf.where(tf.math.not_equal(res, -1)))
        res = (
            tf.strings.reduce_join(num_to_char(res))
            .numpy()
            .decode("utf-8")
            .replace("[UNK]", "")
        )
        output_text.append(res)
    return output_text

def predictor(image_path):
        bbox, cropped_word = detect_and_crop_word(image_path)
        output_path = 'cropped_words.png'  
        plt.imshow(cropped_word)
        plt.show()
        plt.imsave(output_path, cropped_word, cmap='gray')


        def process_images_for_pred(image_path):
            image = preprocess_image(image_path)
            return {"image": image}

        def prepare_dataset_for_pred(image_paths):
            dataset = tf.data.Dataset.from_tensor_slices(image_paths).map(
                process_images_for_pred, num_parallel_calls=AUTOTUNE
            )
            return dataset.batch(batch_size).cache().prefetch(AUTOTUNE)

        test_image_path = 'cropped_words.png'

        image = plt.imread(test_image_path)

        processed_dataset = prepare_dataset_for_pred([test_image_path]) 

        for batch in processed_dataset.take(1):
            processed_image = batch['image'][0].numpy()

        predictions = pred_model.predict(processed_dataset)

        predictions = decode_batch_predictions(predictions)

        print(f"Predicted text: {predictions[0]}")
        rotated_image = np.rot90(processed_image)
        return predictions[0]

model = build_model()
model = load_model("myapp/my_complete_model.h5", custom_objects={"CTCLayer": CTCLayer})
model.training = False
pred_model = keras.models.Model(
    model.get_layer(name="image").output, model.get_layer(name="dense2").output
)

import io
import matplotlib.pyplot as plt
import matplotlib.image as mpimg
from django.http import HttpResponse

class PredictImageView(APIView):
    def post(self, request):
        if 'image' not in request.data:
            return Response({"error": "No image provided"}, status=400)
        
        # Serialize the incoming data
        serializer = ImageSerializer(data=request.data)
        print(serializer)
        if serializer.is_valid():
            # Save the image instance
            image_instance = serializer.save()
            
            # Construct the full path of the stored image
            image_path = os.path.join(settings.MEDIA_ROOT, image_instance.image.name)
            img = detect_and_crop_word(image_path, padding=10)
            prediction = predictor(image_path)
            print("thiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiis : ", prediction)
            # Save the prediction result
            image_instance.prediction = prediction
            image_instance.save()
            # Respond with the prediction and image URL
            return Response({
                "message": "Prediction made successfully",
                "prediction": prediction,
                "image_url": request.build_absolute_uri(image_instance.image.url),
                "image_path": image_path,  # Include the image path in the response if needed
            })
            
        
        # If the serializer is invalid, return the errors
        return Response(serializer.errors, status=400)

