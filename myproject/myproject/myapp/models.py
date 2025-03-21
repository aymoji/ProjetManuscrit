from django.db import models

class Image(models.Model):
    image = models.ImageField(upload_to='images/')
    prediction = models.TextField(null=True, blank=True)
    def __str__(self):
        return f"Prediction: {self.prediction}"

