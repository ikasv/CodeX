import React, {useState} from 'react'

const Home = () => {

    const [state, setstate] = useState('')
    const handleSubmit = event =>
    {
        alert('sdf');
    }

    const nameHandler = event =>{
        setstate(event.target.value)
        // alert(event.target.value)
    
    }
    return (
        <>
            <form onSubmit={handleSubmit}>
                <input type="text" onChange={nameHandler} />
                <input type="submit" value="Submit"/>
            </form>

            {state}
        </>
    )
}

export default Home
