from django.shortcuts import render
#from django.http import HttpResponse

# Create your views here.

def index(request):
    hello = [
        {'title' : 'A First title',
          'location' : 'Paris',
          'slug' : 'link-1'   
        },
        {'title' : 'A Second title',
         'location' : 'Mumbai',
         'slug' : 'link-2'  
        }
        
    ]
    return render(request,'hello/index.html', {'hellodata' : hello})

def details(request, slug):
    details = {
        'title' : 'A First title',
        'description' : 'First Description'
    }
    return render(request,'hello/details.html', details)