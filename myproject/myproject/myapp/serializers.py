from rest_framework import serializers
from .models import Image

class ImageSerializer(serializers.ModelSerializer):
    class Meta:
        model = Image
        fields = ['image', 'prediction']
        extra_kwargs = {
            'prediction': {'required': False}  # Make prediction field optional
        }
