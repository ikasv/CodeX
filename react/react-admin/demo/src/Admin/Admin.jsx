import React, { useEffect } from 'react'
import Dashboard from './Dashboard'
import {Helmet} from "react-helmet";
const Admin = (prop) => {
    var a = <></>
    if(prop.platform == "admin"){
         a = <Helmet>
              <link rel="stylesheet" href={process.env.PUBLIC_URL + '/admin.css'}/>
         </Helmet>
        
        console.log(prop.platform);
    }

    return (
    <>
        {a}
        <Dashboard/>
    </>
    )
}

export default Admin
