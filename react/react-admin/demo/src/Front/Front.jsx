import React, { useEffect } from 'react'
import Home from './Home'
import {Helmet} from "react-helmet";

function Front(prop) {
    console.log(prop.platform);
        
    var a = <></>
    if(prop.platform == "front"){
         a = <Helmet>
              <link rel="stylesheet" href={process.env.PUBLIC_URL + '/main.css'}/>
         </Helmet>
        
        console.log(prop.platform);
    }

    return (
        <>
           {a} 
            <Home/>

        </>
    )
}

export default Front
