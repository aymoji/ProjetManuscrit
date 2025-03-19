from flask import Flask, request, jsonify
import numpy as np
import tensorflow as tf
from tensorflow.keras.models import load_model
from tensorflow.keras import layers
import pickle

class CTCLayer(tf.keras.layers.Layer):
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

model = load_model("my_complete_model.h5", custom_objects={"CTCLayer": CTCLayer})

with open('characters.pkl', 'rb') as file:
    characters = pickle.load(file)

app = Flask(__name__)

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()
    image = np.array(data['image'])
    image = np.expand_dims(image, axis=-1) 
    image = np.expand_dims(image, axis=0)   

    prediction = model.predict(image)
    prediction_text = ''.join([characters[i] for i in np.argmax(prediction, axis=-1)])

    return jsonify({'prediction': prediction_text})

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000)
