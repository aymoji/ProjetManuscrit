from rest_framework.routers import DefaultRouter
from django.urls import path, include
from .views import ImageViewSet, PredictImageView


router = DefaultRouter()
router.register(r'images', ImageViewSet, basename='image')


urlpatterns = [
    path('predict/', PredictImageView.as_view(), name='predict_image'),
    path('', include(router.urls)), 
]
