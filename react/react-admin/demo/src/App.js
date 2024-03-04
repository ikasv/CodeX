import React, { useEffect, useState, useRef } from 'react';

import { BrowserRouter as Router,Routes, Route, Link , useLocation} from 'react-router-dom';

import Admin from './Admin/Admin'

import Front from './Front/Front'



function App() {


    
  return (
   <>

   <Link to='/' >Home</Link>
   <Link to='/admin'>Admin</Link>
      <Routes>
        
      <Route exact path='/' element={<Front  platform = "front" />}></Route>
      <Route exact path='/admin' element={< Admin  platform = "admin" />}></Route>
                 
      </Routes>
   </>
  );
}

export default App;
