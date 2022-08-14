<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    

    public function index(){

        $search = request('search');

        if($search){
            //faz a busca no banco por o que o usuario digitou no form
            $events = Event::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();

        }else{
            //traz todos dados do banco de dados
            $events = Event::all();

        }


        return view('welcome', ['events' => $events, 'search' => $search]);
    }

    public function create(){

        return view('events.create');
    }

    public function store (Request $request){

        $event = new Event;

        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;

        // Image Upload

        if($request->hasFile('image') && $request->file('image')->isValid()){

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            // create a hash path name for this image, save this path in bd
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            // o metodo move salva a img e o public path pega o caminho do diretorio public para a pasta img e cria a pasta events(se ja existir ele vai ate ela)
            $requestImage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;
        }

        $user = auth()->user();
        $event->user_id = $user->id;

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso');

    }

    public function show($id){

        //resgate de dados
        $event = Event::findOrFail($id);

        $user = auth()->user();

        $hasUserJoined = false;

        if($user){

            $userEvents = $user->eventAsParticipant->toArray();

            foreach($userEvents as $userEvent){
                if($userEvent['id'] == $id){
                    $hasUserJoined = true;
                }
            }
        }

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.show',['event' => $event, 'eventOwner' => $eventOwner, 'hasUserJoined' => $hasUserJoined]);

    }

    public function dashboard(){

        $user = auth()->user();

        $events = $user->events;

        $eventAsParticipant = $user->eventAsParticipant;

        return view('events.dashboard', ['events' => $events, 'eventAsParticipant' => $eventAsParticipant]);
    }

    public function destroy($id) {

        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg', 'Evento excluido com sucesso!');

    }

    public function edit($id) {

        $user = auth()->user();

        $event = Event::findOrFail($id);

        if($user->id != $event->user_id){
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request){

        $data = $request->all();

        if($request->hasFile('image') && $request->file('image')->isValid()){

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            // create a hash path name for this image, save this path in bd
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            // o metodo move salva a img e o public path pega o caminho do diretorio public para a pasta img e cria a pasta events(se ja existir ele vai ate ela)
            $requestImage->move(public_path('img/events'), $imageName);

            $data['image'] = $imageName;
        }

        Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }

    public function joinevent($id){

        $user = auth()->user();

        $user->eventAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento '.$event->title);
    }

    public function leaveEvent($id){
        
        $user = auth()->user();

        $user->eventAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Você saiu com sucesso do evento '.$event->title);
    }
}
