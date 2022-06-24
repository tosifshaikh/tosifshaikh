from django import views
from django.urls import path
from . import views

urlpatterns = [
    path('hello/', views.index, name='all-hello'),
    path('hello/<slug:slug>', views.details,name='details')
]
