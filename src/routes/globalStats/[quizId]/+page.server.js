export const load = ({ fetch, params }) => {
	const fetchQuestions = async (id) => {
		const res = await fetch(`http://localhost/backend-quiz-daver/backend-quizSvelte/get-globalRank.php?quizId=${id}`);
		const data = await res.json();
		return data;
	};

	return fetchQuestions(params.quizId);
};
