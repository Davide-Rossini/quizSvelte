export const load=({fetch, params})=>{

    const fetchQuestions = async() =>{
        const res =await fetch (`http://localhost/backend-quiz-daver/backend-quizSvelte/get-quiz.php`);
        const data = await res.json()
        return data
    
    }
    
    return fetchQuestions(params.quizId)
    }