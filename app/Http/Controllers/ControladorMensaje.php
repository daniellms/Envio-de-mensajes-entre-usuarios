<?php

namespace App\Http\Controllers;

use App\User;
use App\Mensaje;
use Carbon\Carbon;
use App\MensajeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\MensajeRequest;
use App\Http\Requests\AtualizarMensaje;
use Symfony\Component\Console\Helper\Table;

class ControladorMensaje extends Controller
{
    

    function __construct()
    {
        $this->middleware('auth');
        
    }

    public function index()
    {   
        return view('mensajes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuarios = User::all();
        return view('mensajes.create', compact('usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MensajeRequest $request)
    {
        
        DB::table('mensajes')->insert([     
                    "motivo" => $request -> input('nombre'),     
                    "mensaje" => $request -> input('mensaje'),
                    "envia_id" => $request -> input('emisorid'),
                    "recibe_id" => $request -> input('correo'),
                    "created_at" => Carbon::now(), // carbon usa fecha, now hora actual
                    "updated_at" => Carbon::now(),
               ]);
               $mensaje= Mensaje::all();
               $msjid =($mensaje->last());
        DB::table('mensaje_user')->insert([
            'mensaje_id' => $msjid->id,
            'user_id' => $request -> input('emisorid'),
            'receptor_id'=> $request -> input('correo'),
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);
         return back()->with('success','Su Mensaje a sido enviado!');;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // envia_id  recibe_id
        $user = User::findOrFail($id);
        $mensajes = DB::table('mensajes')->get();
        foreach ($mensajes as $mensaje)
        {
            if($mensaje->envia_id === $user->id)
            {
                $mismsjs[] = $mensaje;
                $enviado[] = User::findOrFail($mensaje->recibe_id);
             }
        }
        return view('mensajes.show',compact('user','mismsjs','enviado'));//(,'mismsjs','mensajes')
    }

    public function bandeja($id)
    {
        
        $user = User::findOrFail($id);
        $mensajes = DB::table('mensajes')->get();
        foreach ($mensajes as $mensaje)
        {
            if($mensaje->recibe_id ===$user->id)
            {
                $mismsjs[] = $mensaje;
                $enviadospor[] = User::findOrFail($mensaje->envia_id);
              }//else{
            //     $mismsjs[] = null;
            //  }
        }
        return view('mensajes.bandeja',compact('user','mismsjs','enviadospor'));
    }

    public function bandejaIndex($id)
    {
        
        $user = User::findOrFail($id);
        $mensajes = DB::table('mensajes')->get();
        foreach ($mensajes as $mensaje)
        {
            if($mensaje->recibe_id ===$user->id)
            {
                $mismsjs[] = $mensaje;
            }
        }
        return view('mensajes.bandejaIndex',compact('user','mismsjs'));
    }
    public function unMensaje($id)
    {
        $mensaje = DB::table('mensajes')->where('id',$id)->first();
        $envio = DB::table('users')->where('id',$mensaje->envia_id)->first();
        return view('mensajes.unico',compact('envio','mensaje'));
    }
   
    public function eviadoIndex($id)
    {
        $user = User::findOrFail($id);
        $mensajes = DB::table('mensajes')->get();
        foreach ($mensajes as $mensaje)
        {
            if($mensaje->envia_id === $user->id)
            {
                $mismsjs[] = $mensaje;
            }
        }
        return view('mensajes.enviado',compact('user','mismsjs'));
    }

    public function enviado ($id)
    {
        $mensaje = DB::table('mensajes')->where('id',$id)->first();
        $destino = DB::table('users')->where('id',$mensaje->recibe_id)->first();
        return view('mensajes.unicoEnviado',compact('destino','mensaje'));
    }

    public function edit($id)
    {
        $mensaje = Mensaje::findOrFail($id);
        return view('mensajes.edit',compact('mensaje'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AtualizarMensaje $request, $id)
    {
       // dd($request);
    //    $mensaje = Mensaje::findOrFail($id);
    //    $mensaje->update($request->all());
       DB::table('mensajes')->where('id',$id)->update([
        "motivo" => $request -> input('motivo'), 
        "mensaje" => $request -> input('mensaje'),
        "updated_at" => Carbon::now()
        ]);
       return back()->with('info','Mensaje Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$desde)
    {
    //     return 'se elimino '.$desde;
    //     
       if($desde === "bandeja")
       {
        DB::table('mensajes')->where('id',$id)->delete();
        //return redirect()->route('mensajes/indexba',auth()->user()->id);
        return redirect()->route('mensajes.bandejaIndex',auth()->user()->id);
        
       }
       else
       {
        DB::table('mensajes')->where('id',$id)->delete();
        return redirect()->route('mensajes.enviados',auth()->user()->id);
        
       }
    }
    
}
